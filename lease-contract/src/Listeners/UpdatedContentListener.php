<?php

namespace Botble\LeaseContract\Listeners;

use Botble\Base\Events\UpdatedContentEvent;
use Exception;
use Botble\LeaseContract\Services\StoreLeaseContractFileService;

class UpdatedContentListener
{

    /**
     * Handle the event.
     *
     * @param UpdatedContentEvent $event
     * @return void
     */
    public function handle(UpdatedContentEvent $event)
    {
        try {
            if ($event->data && in_array(get_class($event->data),
                config('plugins.lease-contract.general.supported', []))) {
                app(StoreLeaseContractFileService::class)->handleUpdate($event->request, $event->data);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
