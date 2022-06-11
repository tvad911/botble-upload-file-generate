<?php

namespace Botble\LeaseContract;

use Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
    	Schema::dropIfExists('lease_contract_files');
    }
}
