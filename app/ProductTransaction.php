<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property float $total
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $Employees_id
 * @property int $Customers_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $isPaid
 * @property int $itemQty
 * @property string $deletedAt
 * @property Customer $customer
 * @property Employee $employee
 * @property Employee $employee
 * @property Employee $employee
 * @property ProductTransactionDetail[] $productTransactionDetails
 */
class ProductTransaction extends Model
{

    use SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;


    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductTransaction';
    protected $softCascade = ['productTransactionDetails'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['total', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'isPaid', 'itemQty', 'deletedAt'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'Customers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Employee', 'Employees_id');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionDetails()
    {
        return $this->hasMany('App\ProductTransactionDetail');
    }
}
