<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property float $price
 * @property string $updatedAt
 * @property string $createdAt
 * @property int $PetTypes_id
 * @property int $PetSizes_id
 * @property int $Services_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $deletedAt
 * @property PetSize $petSize
 * @property PetType $petType
 * @property Service $service
 * @property Employee $employee
 * @property Employee $employee
 * @property ServiceTransactionDetail[] $serviceTransactionDetails
 */
class ServiceDetail extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ServiceDetails';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['Services_id', 'PetSizes_id', 'PetTypes_id', 'price', 'updatedAt', 'createdAt', 'createdBy', 'updatedBy', 'deletedAt'];

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
