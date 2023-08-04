<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseCollection;
use App\Http\Resources\ExpenseResource;
use App\Models\Warehouse;
use App\Repositories\ExpenseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ExpenseAPIController
 */
class ExpenseAPIController extends AppBaseController
{
    /** @var ExpenseRepository */
    private $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * @param  Request  $request
     * @return ExpenseCollection
     */
    public function index(Request $request)
    {
        $perPage = getPageSize($request);
        $expenses = $this->expenseRepository;
        if ($request->get('warehouse_id')) {
            $expenses->where('warehouse_id', $request->get('warehouse_id'));
        }
        $search = $request->filter['search'] ?? '';
        $warehouse = (Warehouse::where('name', 'LIKE', "%$search%")->get()->count() != 0);
        if ($warehouse) {
            $expenses->whereHas('warehouse', function (Builder $q) use ($search, $warehouse) {
                if ($warehouse) {
                    $q->where('name', 'LIKE', "%$search%");
                }
            });
        }
        $expenses = $expenses->paginate($perPage);
        ExpenseResource::usingWithCollection();

        return new ExpenseCollection($expenses);
    }

    /**
     * @param  CreateExpenseRequest  $request
     * @return ExpenseResource
     */
    public function store(CreateExpenseRequest $request)
    {
        $input = $request->all();
        $expense = $this->expenseRepository->storeExpense($input);

        return new ExpenseResource($expense);
    }

    /**
     * @param $id
     * @return ExpenseResource
     */
    public function show($id)
    {
        $expense = $this->expenseRepository->find($id);

        return new ExpenseResource($expense);
    }

    /**
     * @param  UpdateExpenseRequest  $request
     * @param $id
     * @return ExpenseResource
     *
     * @throws ValidatorException
     */
    public function update(UpdateExpenseRequest $request, $id)
    {
        $input = $request->all();
        $expense = $this->expenseRepository->update($input, $id);

        return new ExpenseResource($expense);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $this->expenseRepository->delete($id);

        return $this->sendSuccess('Expense deleted successfully');
    }
}
