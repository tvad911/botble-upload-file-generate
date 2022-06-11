<?php

namespace Botble\UploadFileGenerator\Providers;

use Botble\UploadFileGenerator\Commands\PluginMakeUploadFileCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginMakeUploadFileCommand::class,
            ]);
        }
    }
}
