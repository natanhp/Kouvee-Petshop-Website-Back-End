<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $productName
 * @property int $productQuantity
 * @property int $productPrice
 * @property string $meassurement
 * @property string $isDeleted
 * @property string $image
 * @property int $minimumQty
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $deletedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $deletedBy
 * @property Employee $employee
 * @property Employee $employee
 * @property Employee $employee
 * @property ProductRestock[] $productRestocks
 * @property ProductTransactionDetail[] $productTransactionDetails
 */
class Products extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Products';

    /**
     * @var array
     */
    protected $fillable = ['productName', 'productQuantity', 'productPrice', 'meassurement', 'isDeleted', 'image', 'minimumQty', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Employee', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeeDeletedBy()
    {
        return $this->belongsTo('App\Employee', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeeUpdatedBy()
    {
        return $this->belongsTo('App\Employee', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRestocks()
    {
        return $this->hasMany('App\ProductRestock', 'Products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionDetails()
    {
        return $this->hasMany('App\ProductTransactionDetail', 'Products_id');
    }
}
