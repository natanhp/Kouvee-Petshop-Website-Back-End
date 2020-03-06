<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $dateBirth
 * @property string $phoneNumber
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $deletedAt
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $deletedBy
 * @property Employee $employee
 * @property Employee $employee
 * @property Employee $employee
 * @property Pet[] $pets
 * @property ProductTransaction[] $productTransactions
 */
class Customers extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Customers';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';


    /**
     * @var array
     */
    protected $fillable = ['name', 'address', 'dateBirth', 'phoneNumber', 'createdAt', 'updatedAt', 'deletedAt', 'createdBy', 'updatedBy', 'deletedBy'];

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
    public function pets()
    {
        return $this->hasMany('App\Pet', 'Customers_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactions()
    {
        return $this->hasMany('App\ProductTransaction', 'Customers_id');
    }
}
