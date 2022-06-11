<?php

namespace Botble\LeaseContract\Providers;

use Botble\LeaseContract\Models\LeaseContract;
use Illuminate\Support\ServiceProvider;
use Botble\LeaseContract\Repositories\Caches\LeaseContractCacheDecorator;
use Botble\LeaseContract\Repositories\Eloquent\LeaseContractRepository;
use Botble\LeaseContract\Repositories\Interfaces\LeaseContractInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Botble\LeaseContract\Repositories\Interfaces\LeaseContractDetailInterface;
use Botble\LeaseContract\Repositories\Caches\LeaseContractDetailCacheDecorator;
use Botble\LeaseContract\Repositories\Eloquent\LeaseContractDetailRepository;
use Botble\LeaseContract\Models\LeaseContractDetail;
use Botble\LeaseContract\Repositories\Interfaces\LeaseContractFileInterface;
use Botble\LeaseContract\Repositories\Caches\LeaseContractFileCacheDecorator;
use Botble\LeaseContract\Repositories\Eloquent\LeaseContractFileRepository;
use Botble\LeaseContract\Models\LeaseContractFile;

class LeaseContractServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(LeaseContractInterface::class, function () {
            return new LeaseContractCacheDecorator(new LeaseContractRepository(new LeaseContract));
        });

        $this->app->bind(LeaseContractDetailInterface::class, function () {
            return new LeaseContractDetailCacheDecorator(new LeaseContractDetailRepository(new LeaseContractDetail));
        });

        $this->app->bind(LeaseContractFileInterface::class, function () {
            return new LeaseContractFileCacheDecorator(new LeaseContractFileRepository(new LeaseContractFile));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/lease-contract')
            ->loadAndPublishConfigurations(['permissions', 'file', 'general'])
            ->loadAndPublishViews()
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web'])
            ->publishAssets();

        $this->app->register(HookServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                //\Language::registerModule([LeaseContract::class]);
                //\Language::registerModule([LeaseContractDetail::class]);
                //\Language::registerModule([\Botble\LeaseContract\Models\LeaseContractFile::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-lease-contract',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/lease-contract::lease-contract.name',
                'icon'        => 'fa fa-home',
                'url'         => route('lease-contract.index'),
                'permissions' => ['lease-contract.index'],
            ]);
            // ->registerItem([
            //     'id'          => 'cms-plugins-lease-contract-detail',
            //     'priority'    => 0,
            //     'parent_id'   => 'cms-plugins-lease-contract',
            //     'name'        => 'plugins/lease-contract::lease-contract-detail.name',
            //     'icon'        => null,
            //     'url'         => route('lease-contract-detail.index'),
            //     'permissions' => ['lease-contract-detail.index'],
            // ]);
            // ->registerItem([
            //     'id'          => 'cms-plugins-lease-contract-file',
            //     'priority'    => 0,
            //     'parent_id'   => 'cms-plugins-lease-contract',
            //     'name'        => 'plugins/lease-contract::lease-contract-file.name',
            //     'icon'        => null,
            //     'url'         => route('lease-contract-file.index'),
            //     'permissions' => ['lease-contract-file.index'],
            // ]);
        });
    }
}
