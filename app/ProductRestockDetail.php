<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $product_restock_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $itemQty
 * @property int $Suppliers_id
 * @property int $Products_id
 * @property int $createdBy
 * @property Employee $employee
 * @property ProductRestock $productRestock
 * @property Product $product
 */
class ProductRestockDetail extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ProductRestockDetail';

    /**
     * @var array
     */
    protected $fillable = ['product_restock_id', 'created_at', 'updated_at', 'updated_at', 'itemQty', 'Products_id', 'createdBy'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Employee', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productRestock()
    {
        return $this->belongsTo('App\ProductRestock', 'product_restock_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'Products_id');
    }
}
