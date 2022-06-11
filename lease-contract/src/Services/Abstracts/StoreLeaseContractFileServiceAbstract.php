<?php

namespace Botble\LeaseContract\Services\Abstracts;

use Botble\LeaseContract\Models\LeaseContract;
use Botble\LeaseContract\Models\LeaseContractFile;
use Botble\LeaseContract\Repositories\Interfaces\LeaseContractFileInterface;
use Illuminate\Http\Request;
use Botble\Media\Services\UploadsManager;
use Botble\Media\Services\ThumbnailService;

abstract class StoreLeaseContractFileServiceAbstract
{
    /**
     * @var UploadsManager
     */
    protected $uploadManager;

    /**
     * @var LeaseContractFileInterface
     */
    protected $leaseContractFileRepository;

    /**
     * @var ThumbnailService
     */
    protected $thumbnailService;

    /**
     * @param LeaseContractFileInterface $leaseContractFileRepository
     * @param UploadsManager $uploadManager
     * @param ThumbnailService $thumbnailService
     */
    public function __construct(
        LeaseContractFileInterface $leaseContractFileRepository,
        UploadsManager $uploadManager,
        ThumbnailService $thumbnailService
    ) {
        $this->leaseContractFileRepository = $leaseContractFileRepository;
        $this->uploadManager = $uploadManager;
        $this->thumbnailService = $thumbnailService;
    }

    /**
     * @param Request $request
     * @param LeaseContract $leaseContract
     * @return mixed
     */
    abstract public function execute(Request $request, LeaseContract $leaseContract);

    /**
     * @param Request $request
     * @param LeaseContract $leaseContract
     * @return mixed
     */
    abstract public function handleUpdate(Request $request, LeaseContract $leaseContract);

    /**
     * @param Request $request
     * @param LeaseContract $leaseContract
     * @return mixed
     */
    abstract public function deleteFile(Request $request, LeaseContract $leaseContract);
}
