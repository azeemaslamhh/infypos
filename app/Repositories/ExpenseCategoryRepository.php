<?php

namespace App\Repositories;

use App\Models\ExpenseCategory;

/**
 * Class ExpenseCategoryRepository
 */
class ExpenseCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'created_at',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ExpenseCategory::class;
    }
}
