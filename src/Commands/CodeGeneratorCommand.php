<?php


namespace Zngue\CodeGenerator\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Container\Container;

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


        $temp = __DIR__.'/../../template/Controller.tpl';

        $name = "cate";
        $key='name';



        $temp= File::get($temp);

        $template = str_replace("{{". $key ."}}", ucwords($name), $temp);

        $savePath = __DIR__.'/../Http/Controller/User/';

        if(!File::exists($savePath)){
            File::makeDirectory($savePath,775);
        }
        File::put($savePath.ucwords($name).'Controller.php',$template);


        echo 111;
    }

}
