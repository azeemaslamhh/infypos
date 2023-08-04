<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreatePurchaseReturnRequest;
use App\Http\Requests\UpdatePurchaseReturnRequest;
use App\Http\Resources\PurchaseReturnCollection;
use App\Http\Resources\PurchaseReturnResource;
use App\Models\PurchaseReturn;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Repositories\PurchaseReturnRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PurchaseReturnAPIController extends AppBaseController
{
    /** @var PurchaseReturnRepository */
    private $purchaseReturnRepository;

    /**
     * PurchaseReturnAPIController constructor.
     *
     * @param  PurchaseReturnRepository  $purchaseReturnRepository
     */
    public function __construct(PurchaseReturnRepository $purchaseReturnRepository)
    {
        $this->purchaseReturnRepository = $purchaseReturnRepository;
    }

    /**
     * @param  Request  $request
     * @return PurchaseReturnCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);
        $search = $request->filter['search'] ?? '';
        $supplier = (Supplier::where('name', 'LIKE', "%$search%")->get()->count() != 0);
        $warehouse = (Warehouse::where('name', 'LIKE', "%$search%")->get()->count() != 0);
        $purchasesReturn = $this->purchaseReturnRepository;
        if ($supplier || $warehouse) {
            $purchasesReturn->whereHas('supplier', function (Builder $q) use ($search, $supplier) {
                if ($supplier) {
                    $q->where('name', 'LIKE', "%$search%");
                }
            })->whereHas('warehouse', function (Builder $q) use ($search, $warehouse) {
                if ($warehouse) {
                    $q->where('name', 'LIKE', "%$search%");
                }
            });
        }

        if ($request->get('start_date') && $request->get('end_date')) {
            $purchasesReturn->whereBetween('date',
                [$request->get('start_date'), $request->get('end_date')]);
        }

        if ($request->get('warehouse_id')) {
            $purchasesReturn->where('warehouse_id', $request->get('warehouse_id'));
        }

        if ($request->get('status')) {
            $purchasesReturn->where('status', $request->get('status'));
        }

        $purchasesReturn = $purchasesReturn->paginate($perPage);
        PurchaseReturnResource::usingWithCollection();

        return new PurchaseReturnCollection($purchasesReturn);
    }

    /**
     * @param  CreatePurchaseReturnRequest  $request
     * @return PurchaseReturnResource
     */
    public function store(CreatePurchaseReturnRequest $request)
    {
        $input = $request->all();
        $purchaseReturn = $this->purchaseReturnRepository->storePurchaseReturn($input);

        return new PurchaseReturnResource($purchaseReturn);
    }

    /**
     * @param $id
     * @return PurchaseReturnResource
     */
    public function show($id)
    {
        $purchaseReturn = $this->purchaseReturnRepository->find($id);

        return new PurchaseReturnResource($purchaseReturn);
    }

    /**
     * @param  PurchaseReturn  $purchasesReturn
     * @return PurchaseReturnResource
     */
    public function edit(PurchaseReturn $purchasesReturn)
    {
        $purchasesReturn = $purchasesReturn->load('purchaseReturnItems.product.stocks', 'warehouse');

        return new PurchaseReturnResource($purchasesReturn);
    }

    /**
     * @param  UpdatePurchaseReturnRequest  $request
     * @param $id
     * @return PurchaseReturnResource
     */
    public function update(UpdatePurchaseReturnRequest $request, $id): PurchaseReturnResource
    {
        $input = $request->all();
        $purchaseReturn = $this->purchaseReturnRepository->updatePurchaseReturn($input, $id);

        return new PurchaseReturnResource($purchaseReturn);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $purchaseReturn = $this->purchaseReturnRepository->where('id', $id)->with('purchaseReturnItems')->first();
            foreach ($purchaseReturn->purchaseReturnItems as $purchaseReturnItem) {
                manageStock(
                    $purchaseReturn->warehouse_id,
                    $purchaseReturnItem['product_id'],
                    $purchaseReturnItem['quantity']
                );
            }
            $this->purchaseReturnRepository->delete($purchaseReturn->id);
            DB::commit();

            return $this->sendSuccess('Purchase Return Deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param  PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseReturnInfo(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn = $purchaseReturn->load(['purchaseReturnItems.product', 'warehouse', 'supplier']);
        $keyName = [
            'email', 'company_name', 'phone', 'address',
        ];
        $purchaseReturn['company_info'] = Setting::whereIn('key', $keyName)->pluck('value', 'key')->toArray();

        return $this->sendResponse($purchaseReturn, 'Purchase Return information retrieved successfully');
    }

    /**
     * @param  PurchaseReturn  $purchaseReturn
     * @return JsonResponse
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function pdfDownload(PurchaseReturn $purchaseReturn): JsonResponse
    {
        $purchaseReturn = $purchaseReturn->load('purchaseReturnItems.product', 'supplier');

        $data = [];
        if (Storage::exists('pdf/purchase_return-'.$purchaseReturn->reference_code.'.pdf')) {
            Storage::delete('pdf/purchase_return-'.$purchaseReturn->reference_code.'.pdf');
        }
        $pdf = PDF::loadView('pdf.purchase-return-pdf', compact('purchaseReturn'))->setOptions([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);

        Storage::disk(config('app.media_disc'))->put('pdf/purchase_return-'.$purchaseReturn->reference_code.'.pdf',
            $pdf->output());
        $data['purchase_return_pdf_url'] = Storage::url('pdf/purchase_return-'.$purchaseReturn->reference_code.'.pdf');

        return $this->sendResponse($data, 'purchase return pdf retrieved Successfully');
    }

    /**
     * @param  Request  $request
     * @return PurchaseReturnCollection
     */
    public function getPurchaseReturnProductReport(Request $request): PurchaseReturnCollection
    {
        $perPage = getPageSize($request);
        $productId = $request->get('product_id');
        $purchaseReturn = $this->purchaseReturnRepository->whereHas('purchaseReturnItems',
            function ($q) use ($productId) {
                $q->where('product_id', '=', $productId);
            })->with(['purchaseReturnItems.product', 'supplier']);

        $purchaseReturn = $purchaseReturn->paginate($perPage);
        PurchaseReturnResource::usingWithCollection();

        return new PurchaseReturnCollection($purchaseReturn);
    }
}
