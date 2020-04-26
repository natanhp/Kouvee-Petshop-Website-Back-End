<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $deletedAt
 * @property string $isArrived
 * @property Employee $employee
 * @property Employee $employee
 * @property Supplier $Suppliers_id
 * @property ProductRestockDetail[] $productRestockDetails
 */
class ProductRestock extends Model
{
    use SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductRestock';

    protected $softCascade = ['productRestockDetails'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'deletedAt', 'isArrived', 'Suppliers_id'];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'Suppliers_id', 'idSupplier');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRestockDetails()
    {
        return $this->hasMany('App\ProductRestockDetail', 'product_restock_id');
    }
}
