<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class PageMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'module:make-page';

    protected $description = 'Create a new page for the specified module.';

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the page.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/assets/js/pages/Index.stub', ['PAGE_NAME' => $this->getPageName()]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $factoryPath = GenerateConfigReader::read('page');

        return $path . $factoryPath->getPath() . '/' . $this->getFileName();
    }

    private function getFileName(): string
    {
        $name = $this->argument('name');

        if (Str::contains(strtolower($name), '/')) {
            $explodedName = explode("/", $name);
            $name = $this->getPageName();

            array_pop($explodedName);
            $extraPath = implode("/", $explodedName);
            return $extraPath . "/" . Str::studly($name) . '.vue';
        }

        return Str::studly($name) . "/" . $this->getPageName() . '.vue';
    }

    function getPageName()
    {
        $name = $this->argument('name');

        if (Str::contains(strtolower($name), '/')) {
            $explodedName = explode("/", $name);
            $explodedName = array_map(function ($item) {
                return Str::studly($item);
            }, $explodedName);
            $name = end($explodedName);
            return Str::studly($name);
        }

        return 'Index';
    }
}
