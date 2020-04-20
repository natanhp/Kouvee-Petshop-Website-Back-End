<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $employee_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $token
 * @property Employee $employee
 */
class FCMModel extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'fcm';

    /**
     * @var array
     */
    protected $fillable = ['employee_id', 'created_at', 'updated_at', 'token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Employee', 'employee_id');
    }
}
