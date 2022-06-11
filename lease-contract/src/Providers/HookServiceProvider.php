<?php

namespace Botble\LeaseContract\Providers;

use Assets;
use Illuminate\Support\ServiceProvider;
use MetaBox;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
    	add_action(BASE_ACTION_META_BOXES, [$this, 'addContractFileBox'], 13, 2);
    }

    /**
     * @param string $context
     * @param $object
     */
    public function addContractFileBox($context, $object)
    {
        if ($object && in_array(get_class($object),
                config('plugins.lease-contract.general.supported', [])) && $context == 'advanced') {
            Assets::addStylesDirectly(['vendor/core/plugins/lease-contract/css/lease-contract-file-admin.css'])
                ->addScriptsDirectly(['vendor/core/plugins/lease-contract/js/lease-contract-file-admin.js'])
                ->addScripts(['sortable']);

            MetaBox::addMetaBox('lease_contract_file_wrap', trans('plugins/lease-contract::lease-contract.contract_file'), [$this, 'contractFileMetaField'],
                get_class($object), $context, 'default');
        }
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function contractFileMetaField()
    {
        $value = null;
        $args = func_get_args();
        if ($args[0] && $args[0]->id) {
            $value = lease_contract_file_data($args[0]);
        }

        return view('plugins/lease-contract::lease-contract-file-box', compact('value'))->render();
    }
}
