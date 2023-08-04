<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitCollection;
use App\Http\Resources\UnitResource;
use App\Repositories\UnitRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UnitAPIController
 */
class UnitAPIController extends AppBaseController
{
    /**
     * @var UnitRepository
     */
    private $unitRepository;

    public function __construct(UnitRepository $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * @param  Request  $request
     * @return UnitCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);
        $units = $this->unitRepository;

        if ($request->get('base_unit')) {
            $units->where('base_unit', $request->get('base_unit'));
        }

        $units = $units->paginate($perPage);

        UnitResource::usingWithCollection();

        return new UnitCollection($units);
    }

    /**
     * @param  CreateUnitRequest  $request
     * @return UnitResource
     *
     * @throws ValidatorException
     */
    public function store(CreateUnitRequest $request)
    {
        $input = $request->all();
        $unit = $this->unitRepository->create($input);

        return new UnitResource($unit);
    }

    /**
     * @param $id
     * @return UnitResource
     */
    public function show($id)
    {
        $unit = $this->unitRepository->find($id);

        return new UnitResource($unit);
    }

    /**
     * @param  UpdateUnitRequest  $request
     * @param $id
     * @return UnitResource
     *
     * @throws ValidatorException
     */
    public function update(UpdateUnitRequest $request, $id)
    {
        $input = $request->all();
        $unit = $this->unitRepository->update($input, $id);

        return new UnitResource($unit);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $unitUse = $this->unitRepository->unitCantDelete($id);
        if ($unitUse) {
            return $this->sendError('Unit can\'t be deleted.');
        }

        $this->unitRepository->delete($id);

        return $this->sendSuccess('Unit deleted successfully');
    }
}
