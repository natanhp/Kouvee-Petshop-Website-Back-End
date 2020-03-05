<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $date
 * @property float $total
 * @property string $updatedAt
 * @property string $createdAt
 * @property string $isDeleted
 * @property int $Pets_id
 * @property int $Employees_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $isPaid
 * @property Employee $employee
 * @property Pet $pet
 * @property Employee $employee
 * @property Employee $employee
 * @property ServiceTransactionDetail[] $serviceTransactionDetails
 */
class ServiceTransaction extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ServiceTransaction';

    /**
     * @var array
     */
    protected $fillable = ['date', 'total', 'updatedAt', 'createdAt', 'isDeleted', 'createdBy', 'updatedBy', 'isPaid'];

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
    public function pet()
    {
        return $this->belongsTo('App\Pet', 'Pets_id');
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
    public function serviceTransactionDetails()
    {
        return $this->hasMany('App\ServiceTransactionDetail');
    }
}
