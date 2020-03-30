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
 * @property string $role
 * @property int $createdBy
 * @property int $updatedBy
 * @property int $deletedBy
 * @property string $username
 * @property string $password
 * @property Employee $employee
 * @property Employee $employee
 * @property Employee $employee
 * @property Customer[] $customers
 * @property Customer[] $customers
 * @property Customer[] $customers
 * @property PetSize[] $petSizes
 * @property PetSize[] $petSizes
 * @property PetSize[] $petSizes
 * @property PetType[] $petTypes
 * @property PetType[] $petTypes
 * @property PetType[] $petTypes
 * @property Pet[] $pets
 * @property Pet[] $pets
 * @property Pet[] $pets
 * @property ProductRestock[] $productRestocks
 * @property ProductRestock[] $productRestocks
 * @property ProductRestock[] $productRestocks
 * @property ProductTransaction[] $productTransactions
 * @property ProductTransaction[] $productTransactions
 * @property ProductTransaction[] $productTransactions
 * @property ProductTransactionDetail[] $productTransactionDetails
 * @property ProductTransactionDetail[] $productTransactionDetails
 * @property Product[] $products
 * @property Product[] $products
 * @property Product[] $products
 * @property ServiceDetail[] $serviceDetails
 * @property ServiceDetail[] $serviceDetails
 * @property ServiceTransaction[] $serviceTransactions
 * @property ServiceTransaction[] $serviceTransactions
 * @property ServiceTransaction[] $serviceTransactions
 * @property ServiceTransactionDetail[] $serviceTransactionDetails
 * @property ServiceTransactionDetail[] $serviceTransactionDetails
 * @property Service[] $services
 * @property Service[] $services
 * @property Service[] $services
 * @property Supplier[] $suppliers
 * @property Supplier[] $suppliers
 * @property Supplier[] $suppliers
 */
class Employee extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'Employees';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    

    /**
     * @var array
     */
    protected $fillable = ['name', 'address', 'dateBirth', 'phoneNumber', 'createdAt', 'updatedAt', 'deletedAt', 'role', 'createdBy', 'updatedBy', 'deletedBy', 'username', 'password'];

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
    public function customersCreatedBy()
    {
        return $this->hasMany('App\Customer', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customersDeletedBy()
    {
        return $this->hasMany('App\Customer', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customersUpdatedBy()
    {
        return $this->hasMany('App\Customer', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petSizesCreatedBy()
    {
        return $this->hasMany('App\PetSize', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petSizesDeletedBy()
    {
        return $this->hasMany('App\PetSize', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petSizesUpdatedBy()
    {
        return $this->hasMany('App\PetSize', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petTypesCreatedBy()
    {
        return $this->hasMany('App\PetType', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petTypesDeletedBy()
    {
        return $this->hasMany('App\PetType', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petTypesUpdatedBy()
    {
        return $this->hasMany('App\PetType', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petsCreatedBy()
    {
        return $this->hasMany('App\Pet', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petsDeletedBy()
    {
        return $this->hasMany('App\Pet', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function petsUpdatedBy()
    {
        return $this->hasMany('App\Pet', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRestocksEmployeesId()
    {
        return $this->hasMany('App\ProductRestock', 'Employees_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRestocksCreatedBy()
    {
        return $this->hasMany('App\ProductRestock', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productRestocksUpdatedBy()
    {
        return $this->hasMany('App\ProductRestock', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionsEmployeesId()
    {
        return $this->hasMany('App\ProductTransaction', 'Employees_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionsCreatedBy()
    {
        return $this->hasMany('App\ProductTransaction', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionsUpdatedBy()
    {
        return $this->hasMany('App\ProductTransaction', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionDetailsCreatedBy()
    {
        return $this->hasMany('App\ProductTransactionDetail', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTransactionDetailsUpdatedBy()
    {
        return $this->hasMany('App\ProductTransactionDetail', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productsCreatedBy()
    {
        return $this->hasMany('App\Product', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productsDeletedBy()
    {
        return $this->hasMany('App\Product', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productsUpdatedBy()
    {
        return $this->hasMany('App\Product', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceDetailsCreatedBy()
    {
        return $this->hasMany('App\ServiceDetail', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceDetailsUpdatedBy()
    {
        return $this->hasMany('App\ServiceDetail', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceTransactionsEmployeesId()
    {
        return $this->hasMany('App\ServiceTransaction', 'Employees_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceTransactionsCreatedBy()
    {
        return $this->hasMany('App\ServiceTransaction', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceTransactionsUpdatedBy()
    {
        return $this->hasMany('App\ServiceTransaction', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceTransactionDetailsCreatedBy()
    {
        return $this->hasMany('App\ServiceTransactionDetail', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceTransactionDetailsUpdatedBy()
    {
        return $this->hasMany('App\ServiceTransactionDetail', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicesCreatedBy()
    {
        return $this->hasMany('App\Service', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicesDeletedBy()
    {
        return $this->hasMany('App\Service', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicesUpdatedBy()
    {
        return $this->hasMany('App\Service', 'updatedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suppliersCreatedBy()
    {
        return $this->hasMany('App\Supplier', 'createdBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suppliersDeletedBy()
    {
        return $this->hasMany('App\Supplier', 'deletedBy');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suppliersUpdatedBy()
    {
        return $this->hasMany('App\Supplier', 'updatedBy');
    }
}
