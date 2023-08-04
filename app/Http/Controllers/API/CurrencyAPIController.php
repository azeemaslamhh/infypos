<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Http\Resources\CurrencyCollection;
use App\Http\Resources\CurrencyResource;
use App\Repositories\CurrencyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class CurrencyAPIController extends AppBaseController
{
    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param  Request  $request
     * @return CurrencyCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);
        $currencies = $this->currencyRepository->paginate($perPage);

        CurrencyResource::usingWithCollection();

        return new CurrencyCollection($currencies);
    }

    /**
     * @param  CreateCurrencyRequest  $request
     * @return CurrencyResource
     *
     * @throws ValidatorException
     */
    public function store(CreateCurrencyRequest $request)
    {
        $input = $request->all();
        $currency = $this->currencyRepository->create($input);

        return new CurrencyResource($currency);
    }

    /**
     * @param $id
     * @return CurrencyResource
     */
    public function show($id)
    {
        $currency = $this->currencyRepository->find($id);

        return new CurrencyResource($currency);
    }

    /**
     * @param  UpdateCurrencyRequest  $request
     * @param $id
     * @return CurrencyResource
     *
     * @throws ValidatorException
     */
    public function update(UpdateCurrencyRequest $request, $id)
    {
        $input = $request->all();
        $currency = $this->currencyRepository->update($input, $id);

        return new CurrencyResource($currency);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if (getSettingValue('currency') == $id) {
            return $this->sendError('Default currency can\'t be deleted.');
        }
        $this->currencyRepository->delete($id);

        return $this->sendSuccess('Currency deleted successfully');
    }
}
