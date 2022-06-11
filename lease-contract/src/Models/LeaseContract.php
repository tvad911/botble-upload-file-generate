<?php

namespace Botble\LeaseContract\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\RealEstate\Models\Currency;
use Botble\LeaseContract\Models\LeaseContractDetail;
use Botble\LeaseContract\Models\LeaseContractFile;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Enums\PaymentTypeEnum;
use Botble\RentContract\Enums\ContractUnitEnum;

class LeaseContract extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lease_contracts';

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'project_id',
        'property_id',
        'price',
        // 'currency',
        'started_at',
        'ended_at',
        'sign_at',
        'services_contract_type',
        'partner',
        'tax_no',
        'delegate_name',
        'delegate_phone',
        'delegate_email',
        'contract_content',
        'payment_type',
        'bank_account_name',
        'bank_account_branch',
        'bank_account_id',
        'payment_description',
        'payment_status',
        'status',
        'unit',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'payment_type'   => PaymentTypeEnum::class,
        'payment_status' => PaymentStatusEnum::class,
        'status'         => BaseStatusEnum::class,
        'unit'         => ContractUnitEnum::class,
    ];

    /**
     * Get the project for the contract.
     */
    public function project()
    {
        return $this->belongsTo('Botble\RealEstate\Models\Project', 'project_id');
    }

    /**
     * Get the property for the contract.
     */
    public function property()
    {
        return $this->belongsTo('Botble\RealEstate\Models\Property', 'property_id');
    }

    /**
     * [currency description]
     * @return [type] [description]
     */
    public function currencyValue()
    {
        return $this->belongsTo('Botble\RealEstate\Models\Currency', 'currency');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contractDetail()
    {
        return $this->hasMany(LeaseContractDetail::class)->orderBy('lease_contract_details.order', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contractFile()
    {
        return $this->hasMany('Botble\LeaseContract\Models\LeaseContractFile', 'lease_contract_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (LeaseContract $leaseContract) {
            LeaseContractDetail::where('lease_contract_id', $leaseContract->id)->delete();
            LeaseContractFile::where('lease_contract_id', $leaseContract->id)->delete();
        });
    }
}
