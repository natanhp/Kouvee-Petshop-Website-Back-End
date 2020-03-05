<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $date
 * @property float $total
 * @property string $isDeleted
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $Employees_id
 * @property int $Customers_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $isPaid
 * @property int $itemQty
 * @property Customer $customer
 * @property Employee $employee
 * @property Employee $employee
 * @property Employee $employee
 * @property ProductTransactionDetail[] $productTransactionDetails
 */
class ProductTransaction extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductTransaction';

    /**
     * @var array
     */
    protected $fillable = ['date', 'total', 'isDeleted', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'isPaid', 'itemQty'];

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
    public function employeeEmployeesId()
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
