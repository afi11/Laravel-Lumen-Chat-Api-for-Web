<?php 

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand 
{
    protected $name = 'make:controller';

    protected $description = 'Create a new controller class';

    protected $type = 'Controller';

    protected function getStub()
    {
        if($this->option('resource')) {
            return __DIR__.'/stubs/controller.stub';
        }
        return __DIR__.'/stubs/controller.plain.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    
    protected function getOptions()
    {
        return [
            ['resource',null,InputOption::VALUE_NONE,'Generate a resource controller class.'],
        ];
    }

}