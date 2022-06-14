<?php

namespace Botble\UploadFileGenerator\Providers;

use Illuminate\Support\ServiceProvider;

class UploadFileGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(CommandServiceProvider::class);
    }
}
