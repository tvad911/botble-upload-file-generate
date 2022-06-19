<?php

namespace Botble\UploadFileGenerator\Commands;

use Botble\DevTool\Commands\Abstracts\BaseMakeCommand;
use File;
use Illuminate\Support\Str;
use League\Flysystem\FileNotFoundException;

class PluginUploadFileCommand extends BaseMakeCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:plugin:make:upload-file-generate {plugin : The plugin name} {name : Model name} {base : Base model name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a upload file code inside a plugin';

    /**
     * Execute the console command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if (!preg_match('/^[a-z0-9\-]+$/i', $this->argument('plugin')) || !preg_match('/^[a-z0-9\-]+$/i',
                $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return 1;
        }

        $plugin = strtolower($this->argument('plugin'));
        $location = plugin_path($plugin);

        if (!File::isDirectory($location)) {
            $this->error('Plugin named [' . $plugin . '] does not exists.');
            return 1;
        }

        $name = strtolower($this->argument('name'));

        $this->publishStubs($this->getStub(), $location);
        $this->removeUnusedFiles($location);
        $this->renameFiles($name, $location);
        $this->searchAndReplaceInFiles($name, $location);
        $this->line('------------------');
        $this->line('<info>The upload file CRUD for plugin </info> <comment>' . $plugin . '</comment> <info>was created in</info> <comment>' . $location . '</comment><info>, customize it!</info>');
        $this->line('------------------');
        $this->call('cache:clear');

        $replacements = [
            'config/permissions.stub',
            'helpers/constants.stub',
            'src/Forms/{Base}Form.stub',
            'src/Models/{Base}.stub',
            'src/Providers/{Module}ServiceProvider.stub',
            'src/Plugin.stub',
            'webpack.mix.js.stub',
        ];

        foreach ($replacements as $replacement) {
            $this->line('Add below code into ' . $this->replacementUploadFileModule(null,
                    str_replace(base_path(), '', $location) . '/' . $replacement));
            $this->info($this->replacementUploadFileModule($replacement));
        }

        return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getStub(): string
    {
        return __DIR__ . '/../../stubs/upload-file';
    }

    /**
     * @param string $location
     */
    protected function removeUnusedFiles(string $location)
    {
        $files = [
            'config/permissions.stub',
            'helpers/constants.stub',
            'src/Forms/{Base}Form.stub',
            'src/Models/{Base}.stub',
            'src/Providers/{Module}ServiceProvider.stub',
            'src/Plugin.stub',
            'webpack.mix.js.stub',
        ];

        foreach ($files as $file) {
            File::delete($location . '/' . $file);
        }
    }

    /**
     * @param string $file
     * @param null $content
     * @return string
     */
    protected function replacementUploadFileModule(string $file = null, $content = null): string
    {
        $name = strtolower($this->argument('name'));

        if ($file && empty($content)) {
            $content = file_get_contents($this->getStub() . '/' . $file);
        }

        $replace = $this->getReplacements($name) + $this->baseReplacements($name);

        return str_replace(array_keys($replace), $replace, $content);
    }

    /**
     * @param string $replaceText
     * @return array
     */
    public function baseReplacements(string $replaceText): array
    {
        return ['.js.stub' => '.js'] + ['.scss.stub' => '.scss']  + parent::baseReplacements($replaceText);
    }

    /**
     * {@inheritDoc}
     */
    public function getReplacements(string $replaceText): array
    {
        $module = strtolower($this->argument('plugin'));
        $base = strtolower($this->argument('base'));

        return [
            '{type}'     => 'plugin',
            '{types}'    => 'plugins',
            '{-module}'  => strtolower($module),
            '{module}'   => Str::snake(str_replace('-', '_', $module)),
            '{+module}'  => Str::camel($module),
            '{modules}'  => Str::plural(Str::snake(str_replace('-', '_', $module))),
            '{Modules}'  => ucfirst(Str::plural(Str::snake(str_replace('-', '_', $module)))),
            '{-modules}' => Str::plural($module),
            '{MODULE}'   => strtoupper(Str::snake(str_replace('-', '_', $module))),
            '{Module}'   => ucfirst(Str::camel($module)),

            '{-base}'  => strtolower($base),
            '{base}'   => Str::snake(str_replace('-', '_', $base)),
            '{+base}'  => Str::camel($base),
            '{bases}'  => Str::plural(Str::snake(str_replace('-', '_', $base))),
            '{Bases}'  => ucfirst(Str::plural(Str::snake(str_replace('-', '_', $base)))),
            '{-bases}' => Str::plural($base),
            '{BASE}'   => strtoupper(Str::snake(str_replace('-', '_', $base))),
            '{Base}'   => ucfirst(Str::camel($base)),
        ];
    }
}
