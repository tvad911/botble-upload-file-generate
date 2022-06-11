<?php

namespace Botble\LeaseContract\Services;

use Botble\LeaseContract\Models\LeaseContract;
use Botble\LeaseContract\Models\LeaseContractFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Botble\Media\Http\Resources\FileResource;
use Botble\LeaseContract\Services\Abstracts\StoreLeaseContractFileServiceAbstract;
use File;
use Image;
use Throwable;
use Auth;

class StoreLeaseContractFileService extends StoreLeaseContractFileServiceAbstract
{
    /**
     * @param Request $request
     * @param LeaseContract $leaseContract
     *
     * @return mixed|void
     */
    public function execute(Request $request, LeaseContract $leaseContract)
    {
        if($request->hasfile('contract_files'))
        {
            foreach($request->file('contract_files') as $file)
            {
                $log = $this->handleUpload($file, 'contracts/lease_contract_files', $leaseContract);
                if($log != null && isset($log['message'])){
                    Log::error($log['message']);
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param LeaseContract $leaseContract
     *
     * @return mixed|void
     */
    public function handleUpdate(Request $request, LeaseContract $leaseContract)
    {
        /**
         * 1. If old file empty remove file
         */
        if (empty(json_decode($request->input('lease_contract_file')))) {
            $files = $this->leaseContractFileRepository->allBy(['lease_contract_id' => $leaseContract->id]);

            if($files->isNotEmpty())
            {
                foreach ($files as $file) {
                    if($this->uploadManager->deleteFile($file->folder . '/' . $file->url))
                    {
                        $this->leaseContractFileRepository->deleteBy(['id' => $file->id, 'lease_contract_id' => $leaseContract->id]);
                    }
                }
            }
        }

        /**
         * 2.Remove old file, update information
         */
        $arrFiles = json_decode($request->input('lease_contract_file'));
        $listFile = collect($arrFiles);

        if($listFile->isNotEmpty())
        {
            // 1. Update description
            foreach ($listFile as $item) {
                $file = $this->leaseContractFileRepository->getFirstBy(['id' => $item->id, 'lease_contract_id' => $leaseContract->id]);
                if($file)
                {
                    $file->options = $item->options;
                    $this->leaseContractFileRepository->createOrUpdate($file);
                }
            }

            // 2. Remove old file
            $arrFile = $listFile->pluck('id')->all();
            $files = $this->leaseContractFileRepository->allBy(['lease_contract_id' => $leaseContract->id, ['id', 'not_in', $arrFile]]);
            if($files->isNotEmpty())
            {
                foreach ($files as $file) {
                    if($this->uploadManager->deleteFile($file->folder . '/' . $file->url))
                    {
                        $this->leaseContractFileRepository->deleteBy(['id' => $file->id, 'lease_contract_id' => $leaseContract->id]);
                    }
                }
            }
        }

        /**
         * 3. Upload new file
         */
        if($request->hasfile('contract_files'))
        {
            foreach($request->file('contract_files') as $file)
            {
                $log = $this->handleUpload($file, 'contracts/lease_contract_files', $leaseContract);
                if($log != null){
                    if(isset($log['message']))
                        Log::error($log['message']);
                }
            }
        }
    }

    /**
     * @param UploadedFile $fileUpload
     * @param int $folderId
     * @param string $path
     * @return JsonResponse|array
     */
    public function handleUpload($fileUpload, $folderPath = 'contracts/lease_contract_files', $leaseContract)
    {
        if (!$fileUpload) {
            return [
                'error'   => true,
                'message' => trans('plugins/lease-contract::media.can_not_detect_file_type'),
            ];
        }

        try {
            $file = $this->leaseContractFileRepository->getModel();

            if ($fileUpload->getSize() / 1024 > (int)config('plugins.lease-contract.file.max_file_size_upload')) {
                return [
                    'error'   => true,
                    'message' => trans('plugins/lease-contract::media.file_too_big',
                        ['size' => config('plugins.lease-contract.file.max_file_size_upload')]),
                ];
            }

            $fileExtension = $fileUpload->getClientOriginalExtension();

            if (!in_array($fileExtension, explode(',', config('plugins.lease-contract.file.allowed_mime_types')))) {
                return [
                    'error'   => true,
                    'message' => trans('plugins/lease-contract::media.can_not_detect_file_type'),
                ];
            }

            $folderPath = $folderPath . '/' . $leaseContract->id;

            $file->name = $this->leaseContractFileRepository->createName(File::name($fileUpload->getClientOriginalName()), $folderPath);

            $fileName = $this->leaseContractFileRepository->createSlug($file->name, $fileExtension, Storage::path($folderPath));

            $filePath = $fileName;

            if ($folderPath) {
                $filePath = $folderPath . '/' . $filePath;
            }

            $content = File::get($fileUpload->getRealPath());

            $this->uploadManager->saveFile($filePath, $content);

            $data = $this->uploadManager->fileDetails($filePath);

            if (empty($data['mime_type'])) {
                return [
                    'error'   => true,
                    'message' => trans('plugins/lease-contract::media.can_not_detect_file_type'),
                ];
            }

            $file->url = $fileName;
            $file->size = $data['size'];
            $file->mime_type = $data['mime_type'];
            $file->folder = $folderPath;
            $file->user_id = Auth::check() ? Auth::user()->getKey() : 0;
            $file->lease_contract_id = $leaseContract->id;
            $this->leaseContractFileRepository->createOrUpdate($file);

            if ($file->canGenerateThumbnails()) {
                foreach (config('plugins.lease-contract.file.sizes', []) as $size) {
                    $readableSize = explode('x', $size);

                    $this->thumbnailService
                        ->setImage($fileUpload->getRealPath())
                        ->setSize($readableSize[0], $readableSize[1])
                        ->setDestinationPath($folderPath)
                        ->setFileName(File::name($fileName) . '-' . $size . '.' . $fileExtension)
                        ->save();
                }
            }

            return [
                'error' => false,
                'data'  => new FileResource($file),
            ];
        } catch (Exception $ex) {
            return [
                'error'   => true,
                'message' => $ex->getMessage(),
            ];
        }
    }
    /**
     * Delete file
     * @param  [type] $listFile [description]
     * @return [type]           [description]
     */
    public function deleteFile(Request $request, LeaseContract $leaseContract)
    {
        try{
            $listFile = $leaseContract->contractFile;
            if(!empty($listFile))
            {
                foreach ($listFile as $file) {
                    $leaseContractFile = $this->leaseContractFileRepository->findOrFail($file->id);
                    if(isset($leaseContractFile))
                    {
                        if($this->uploadManager->deleteFile($leaseContractFile->folder . '/' . $leaseContractFile->url))
                        {
                            $this->leaseContractFileRepository->deleteBy(['id' => $leaseContractFile->id, 'lease_contract_id' => $leaseContract->id]);
                        }
                    }
                }

                $this->uploadManager->deleteDirectory($leaseContractFile->folder);
            }

        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}