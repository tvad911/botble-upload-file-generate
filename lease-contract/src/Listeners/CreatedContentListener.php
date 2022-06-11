<?php

namespace Botble\LeaseContract\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Exception;
use Botble\LeaseContract\Services\StoreLeaseContractFileService;

class CreatedContentListener
{

    /**
     * Handle the event.
     *
     * @param CreatedContentEvent $event
     * @return void
     */
    public function handle(CreatedContentEvent $event)
    {
        try {
            if ($event->data && in_array(get_class($event->data),
                config('plugins.lease-contract.general.supported', []))) {
                app(StoreLeaseContractFileService::class)->execute($event->request, $event->data);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
