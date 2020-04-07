<?php

namespace Zngue\CodeGenerator\Provider;
use Illuminate\Support\ServiceProvider;
use Zngue\CodeGenerator\Commands\CodeGeneratorCommand;

class CodeGeneratorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //$this->commands([CodeGeneratorCommand::class]);

        $this->loadRoutesFrom(__DIR__ . "/../../routes/web.php");
        $this->loadViewsFrom(__DIR__ . '/../../views', 'zng');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CodeGeneratorCommand::class
            ]);
        }

    }
}
