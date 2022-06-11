<?php

namespace Botble\LeaseContract\Listeners;

use Botble\Base\Events\DeletedContentEvent;
use Exception;
use Botble\LeaseContract\Services\StoreLeaseContractFileService;

class DeletedContentListener
{

    /**
     * Handle the event.
     *
     * @param DeletedContentEvent $event
     * @return void
     */
    public function handle(DeletedContentEvent $event)
    {
        try {
            if ($event->data && in_array(get_class($event->data),
                config('plugins.lease-contract.general.supported', []))) {
                app(StoreLeaseContractFileService::class)->deleteFile($event->request, $event->data);
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
