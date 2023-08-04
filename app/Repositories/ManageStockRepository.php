<?php

namespace App\Repositories;

use App\Models\ManageStock;

/**
 * Class ManageStockRepository
 */
class ManageStockRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quantity',
    ];

    /**
     * @var string[]
     */
    protected $allowedFields = [
        'quantity',
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
        return ManageStock::class;
    }
}
