<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $isArrived
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $Suppliers_id
 * @property int $Products_id
 * @property int $Employees_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $itemQty
 * @property string $deletedAt
 * @property Employee $employee
 * @property Product $product
 * @property Supplier $supplier
 * @property Employee $employee
 * @property Employee $employee
 */
class ProductRestock extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductRestock';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['isArrived', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'itemQty', 'deletedAt'];

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
