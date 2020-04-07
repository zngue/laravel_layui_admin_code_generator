<?php


namespace Zngue\CodeGenerator\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Container\Container;
use Zngue\User\Service\toolesService;

class CodeGeneratorCommand extends Command
{

    protected $name = 'zng:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a laralib scaffold';
    public function handle(){
//        $this->call("vendor:publish",['--provider'=>'Zngue\User\UserService']);
//        $this->call("vendor:publish",['--provider'=>'Zngue\Module\Provider\ModuleServiceProvider']);
        $this->call('zng:module');
		$this->call('vendor:publish',['--provider'=>'Zngue\CodeGenerator\Provider\CodeGeneratorServiceProvider']);
        $data=array(
            'dbhost'=>config('database.connections.mysql.host'),
            'dbuser'=> config('database.connections.mysql.username'),
            'dbpw'=>config('database.connections.mysql.password'),
            'dbname'=>config('database.connections.mysql.database')
        );
        $file = __DIR__.'/../../sql/code.sql';
        $tool=new toolesService($data);
        $data =$tool->import_data($file,config('database.connections.mysql.prefix'));
        echo $data['info'];
    }

}
