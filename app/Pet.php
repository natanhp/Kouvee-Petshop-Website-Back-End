<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $dateBirth
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $deletedAt
 * @property int $Customers_id
 * @property int $PetSizes_id
 * @property int $PetTypes_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $deletedBy
 * @property Customer $customer
 * @property PetSize $petSize
 * @property PetType $petType
 * @property Employee $employee
 * @property Employee $employee
 * @property Employee $employee
 * @property ServiceTransaction[] $serviceTransactions
 */
class Pet extends Model
{
    use SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Pets';
    protected $softCascade = ['serviceTransactions'];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['name', 'dateBirth', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy'];

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
    public function serviceTransactions()
    {
        return $this->hasMany('App\ServiceTransaction', 'Pets_id');
    }
}
