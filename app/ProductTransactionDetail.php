<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $Products_id
 * @property string $ProductTransaction_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $deletedAt
 * @property ProductTransaction $productTransaction
 * @property Product $product
 * @property Employee $employee
 * @property Employee $employee
 */
class ProductTransactionDetail extends Model
{
    use SoftDeletes;


    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductTransactionDetail';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'deletedAt'];

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
