<?php

namespace Botble\LeaseContract\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Support\Facades\Storage;
use RvMedia;

class LeaseContractFile extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lease_contract_files';

    /**
     * @var array
     */
    protected $fillable = [
        'lease_contract_id',
        'name',
        'folder',
        'mime_type',
        'size',
        'url',
        'options',
    ];

    /**
     * Get the lease contract that owns the file.
     */
    public function leaseContract()
    {
        return $this->belongsTo('Botble\LeaseContract\Models\LeaseContract', 'lease_contract_id');
    }

    /**
     * @return bool
     */
    public function canGenerateThumbnails(): bool
    {
        return RvMedia::isImage($this->mime_type) &&
            !in_array($this->mime_type, ['image/svg+xml', 'image/x-icon', 'image/gif']) &&
            Storage::exists($this->folder . '/' . $this->url);
    }
}
