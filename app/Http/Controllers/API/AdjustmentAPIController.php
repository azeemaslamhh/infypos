<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CraeteAdjustmentRequest;
use App\Http\Requests\UpdateAdjustmentRequest;
use App\Http\Resources\AdjustmentCollection;
use App\Http\Resources\AdjustmentResource;
use App\Models\Adjustment;
use App\Models\AdjustmentItem;
use App\Models\ManageStock;
use App\Repositories\AdjustmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AdjustmentAPIController extends AppBaseController
{
    /** @var AdjustmentRepository */
    private $adjustmentRepository;

    public function __construct(AdjustmentRepository $adjustmentRepository)
    {
        $this->adjustmentRepository = $adjustmentRepository;
    }

    /**
     * @param  Request  $request
     * @return AdjustmentCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);

        $adjustments = $this->adjustmentRepository;

        if ($request->get('warehouse_id')) {
            $adjustments->where('warehouse_id', $request->get('warehouse_id'));
        }

        $adjustments = $adjustments->paginate($perPage);

        AdjustmentResource::usingWithCollection();

        return new AdjustmentCollection($adjustments);
    }

    /**
     * @param  Request  $request
     * @return AdjustmentResource
     */
    public function store(CraeteAdjustmentRequest $request)
    {
        $input = $request->all();
        $adjustment = $this->adjustmentRepository->storeAdjustment($input);

        return new AdjustmentResource($adjustment);
    }

    /**
     * @param  Adjustment  $adjustment
     * @return AdjustmentResource
     */
    public function show(Adjustment $adjustment)
    {
        $adjustment = $adjustment->load('adjustmentItems.product');

        return new AdjustmentResource($adjustment);
    }

    /**
     * @param  Adjustment  $adjustment
     * @return AdjustmentResource
     */
    public function edit(Adjustment $adjustment)
    {
        $adjustment = $adjustment->load('adjustmentItems.product.stocks', 'warehouse');

        return new AdjustmentResource($adjustment);
    }

    /**
     * @param  UpdateAdjustmentRequest  $request
     * @param $id
     * @return AdjustmentResource
     */
    public function update(UpdateAdjustmentRequest $request, $id)
    {
        $input = $request->all();
        $adjustment = $this->adjustmentRepository->updateAdjustment($input, $id);

        return new AdjustmentResource($adjustment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $adjustment = $this->adjustmentRepository->with('adjustmentItems')->where('id', $id)->firstOrFail();

            foreach ($adjustment->adjustmentItems as $adjustmentItem) {
                $oldItem = AdjustmentItem::whereId($adjustmentItem->id)->firstOrFail();
                $existProductStock = ManageStock::whereWarehouseId($adjustment->warehouse_id)->whereProductId($oldItem->product_id)->first();

                if ($oldItem->method_type == AdjustmentItem::METHOD_ADDITION) {
                    $totalQuantity = $existProductStock->quantity - $oldItem['quantity'];
                } else {
                    $totalQuantity = $existProductStock->quantity + $oldItem['quantity'];
                }

                $existProductStock->update([
                    'quantity' => $totalQuantity,
                ]);
            }

            $this->adjustmentRepository->delete($id);

            DB::commit();

            return $this->sendSuccess('Adjustment delete successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
