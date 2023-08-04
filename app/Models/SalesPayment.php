<?php

namespace App\Models;

use App\Models\Contracts\JsonResourceful;
use App\Traits\HasJsonResourcefulData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SalesPayment
 *
 * @property int $id
 * @property int $sale_id
 * @property int $reference
 * @property string $payment_date
 * @property int|null $payment_type
 * @property float|null $amount
 * @property float|null $received_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereReceivedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalesPayment extends BaseModel implements JsonResourceful
{
    use HasFactory, HasJsonResourcefulData;

    protected $table = 'sales_payments';

    public const JSON_API_TYPE = 'sales_payments';

    public const CASH = 1;

    public const CHEQUE = 2;

    public const BANK_TRANSFER = 3;

    public const OTHER = 4;

    /**
     * @var string[]
     */
    protected $fillable = [
        'sale_id',
        'reference',
        'payment_date',
        'payment_type',
        'amount',
        'received_amount',
    ];

    /**
     * @var string[]
     */
    public static $rules = [
        'payment_date' => 'date',
        'amount' => 'required|numeric',
    ];

    /**
     * @return array
     */
    public function prepareLinks(): array
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function prepareAttributes(): array
    {
        $fields = [
            'sale_id' => $this->sale_id,
            'reference' => $this->reference,
            'payment_date' => $this->payment_date,
            'payment_type' => $this->payment_type,
            'amount' => $this->amount,
            'received_amount' => $this->received_amount,
        ];

        return $fields;
    }

    /**
     * @return BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }
}
