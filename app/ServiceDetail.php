<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float $price
 * @property string $isDeleted
 * @property string $updatedAt
 * @property string $createdAt
 * @property int $PetTypes_id
 * @property int $PetSizes_id
 * @property int $Services_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property PetSize $petSize
 * @property PetType $petType
 * @property Service $service
 * @property Employee $employee
 * @property Employee $employee
 * @property ServiceTransactionDetail[] $serviceTransactionDetails
 */
class ServiceDetails extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ServiceDetails';

    /**
     * @var array
     */
    protected $fillable = ['price', 'isDeleted', 'updatedAt', 'createdAt', 'createdBy', 'updatedBy'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petSize()
    {
        return $this->belongsTo('App\PetSize', 'PetSizes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petType()
    {
        return $this->belongsTo('App\PetType', 'PetTypes_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo('App\Service', 'Services_id');
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
        return $this->hasMany('App\ServiceTransactionDetail', 'ServiceDetails_id');
    }
}
