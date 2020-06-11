<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $isFinished
 * @property string $createdAt
 * @property string $updatedAt
 * @property int $ServiceDetails_id
 * @property int $createdBy
 * @property int $updatedBy
 * @property string $ServiceTransaction_id
 * @property string $deletedAt
 * @property ServiceDetail $serviceDetail
 * @property ServiceTransaction $serviceTransaction
 * @property Employee $employee
 * @property Employee $employee
 */
class ServiceTransactionDetail extends Model
{
    use SoftDeletes;
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ServiceTransactionDetail';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * @var array
     */
    protected $fillable = ['isFinished', 'createdAt', 'updatedAt', 'createdBy', 'updatedBy', 'deletedAt'];

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
        return $this->belongsTo('App\ServiceTransaction', 'ServiceDetails_id');
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
