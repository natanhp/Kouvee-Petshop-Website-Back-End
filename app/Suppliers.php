<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $idSupplier
 * @property string $name
 * @property string $address
 * @property string $phoneNumber
 * @property string $isDeleted
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
 */
class Suppliers extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Suppliers';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'idSupplier';

    /**
     * @var array
     */
    protected $fillable = ['name', 'address', 'phoneNumber', 'isDeleted', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy'];

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
    public function productRestocks()
    {
        return $this->hasMany('App\ProductRestock', 'Suppliers_id', 'idSupplier');
    }
}
