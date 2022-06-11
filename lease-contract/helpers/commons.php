<?php

use Botble\LeaseContract\Repositories\Interfaces\LeaseContractInterface;
use Botble\LeaseContract\Repositories\Interfaces\LeaseContractFileInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

if (!function_exists('lease_contract_file_data')) {
    /**
     * @param Model $object
     * @param array $select
     * @return array
     */
    function lease_contract_file_data($object, array $with = [] ,array $select = ['id', 'lease_contract_id', 'name', 'folder', 'mime_type', 'size', 'url', 'options']): array
    {
        $list = app(LeaseContractFileInterface::class)->allBy(['lease_contract_id' => $object->id], $with, $select);
        if (!empty($list)) {
            return $list->toArray() ?? [];
        }
        return [];
    }
}

if (!function_exists('get_lease_contract_thumbnail_image_file')) {
    /**
     * @param Model $object
     * @param array $select
     * @return array
     */
    function get_lease_contract_thumbnail_image_file($mime_type)
    {
        switch ($mime_type) {
            case 'application/pdf':
                return 'lease-contract/images/pdf.png';
                break;

            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                return 'lease-contract/images/word.png';
                break;

            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                return 'lease-contract/images/excel.png';
                break;

            case 'application/zip':
            case 'application/x-gzip':
                return 'lease-contract/images/zip.png';
                break;

            case 'application/vnd.rar':
                return 'lease-contract/images/rar.png';
                break;

            default:
                return '';
                break;
        }
    }
}