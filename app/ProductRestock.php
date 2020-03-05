<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $date
 * @property string $isDeleted
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $Suppliers_id
 * @property int $Products_id
 * @property int $Employees_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $itemQty
 * @property Employee $employee
 * @property Product $product
 * @property Supplier $supplier
 * @property Employee $employee
 * @property Employee $employee
 */
class ProductRestock extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductRestock';

    /**
     * @var array
     */
    protected $fillable = ['date', 'isDeleted', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'itemQty'];

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
    public function product()
    {
        return $this->belongsTo('App\Product', 'Products_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'Suppliers_id', 'idSupplier');
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
