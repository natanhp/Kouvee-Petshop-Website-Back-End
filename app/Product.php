<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string $productName
 * @property int $productQuantity
 * @property int $productPrice
 * @property string $meassurement
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
class Product extends Model
{
    use SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Products';
    protected $softCascade = ['productRestockDetails', 'productTransactionDetails'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['productName', 'productQuantity', 'productPrice', 'meassurement', 'image', 'minimumQty', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy'];

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
    public function productRestockDetails()
    {
        return $this->hasMany('App\ProductRestockDetail', 'Products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionDetails()
    {
        return $this->hasMany('App\ProductTransactionDetail', 'Products_id');
    }
}
