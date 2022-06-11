<?php

namespace Botble\LeaseContract\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\LeaseContract\Repositories\Interfaces\LeaseContractFileInterface;

class LeaseContractFileCacheDecorator extends CacheAbstractDecorator implements LeaseContractFileInterface
{
	/**
     * {@inheritdoc}
     */
    public function createName($name, $folder)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function checkIfExistsName($name, $folder)
    {
    	return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function createSlug($name, $extension, $folderPath)
    {
    	return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
