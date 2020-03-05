<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $serviceName
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
 * @property ServiceDetail[] $serviceDetails
 */
class Services extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Services';

    /**
     * @var array
     */
    protected $fillable = ['serviceName', 'isDeleted', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy'];

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
    public function serviceDetails()
    {
        return $this->hasMany('App\ServiceDetail', 'Services_id');
    }
}
