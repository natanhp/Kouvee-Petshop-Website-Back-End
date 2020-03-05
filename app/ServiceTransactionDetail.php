<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $isDeleted
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $ServiceDetails_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $ServiceTransaction_id
 * @property ServiceDetail $serviceDetail
 * @property ServiceTransaction $serviceTransaction
 * @property Employee $employee
 * @property Employee $employee
 */
class ServiceTransactionDetail extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ServiceTransactionDetail';

    /**
     * @var array
     */
    protected $fillable = ['isDeleted', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceDetail()
    {
        return $this->belongsTo('App\ServiceDetail', 'ServiceDetails_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function serviceTransaction()
    {
        return $this->belongsTo('App\ServiceTransaction');
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
