<?php

namespace AzizSama\MenuBuilder\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class FilterMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Menu Builder Filter class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Filter';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!is_dir(app_path('Filters')) && !file_exists(app_path('Filters'))) {
            mkdir(app_path('Filters'));
        }
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Filters')) ? $rootNamespace . '\\Filters' : $rootNamespace;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('stubs/filter.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : $this->packagePath("../resources/$stub");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the class already exists'],
        ];
    }

    /**
     * Get the package path.
     * 
     * @param string $path
     * @return string
     */
    protected function packagePath($path = '')
    {
        if (Str::startsWith($path, DIRECTORY_SEPARATOR)) {
            $path = substr($path, 1);
        }
        return __DIR__ . '/../../' . $path;
    }
}
