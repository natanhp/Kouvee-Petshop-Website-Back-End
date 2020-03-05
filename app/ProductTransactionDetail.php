<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $isDeleted
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $Products_id
 * @property string $ProductTransaction_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property ProductTransaction $productTransaction
 * @property Product $product
 * @property Employee $employee
 * @property Employee $employee
 */
class ProductTransactionDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductTransactionDetail';

    /**
     * @var array
     */
    protected $fillable = ['isDeleted', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productTransaction()
    {
        return $this->belongsTo('App\ProductTransaction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'Products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeeCreatedBy()
    {
        return $this->belongsTo('App\Employee', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeeUpdatedBy()
    {
        return $this->belongsTo('App\Employee', 'updatedBy');
    }
}
