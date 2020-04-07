<?php


namespace Zngue\CodeGenerator\Service;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Zngue\CodeGenerator\Models\CodeModel;
use Zngue\CodeGenerator\Models\NameSpaceModel;
use Zngue\Module\Models\FieldModel;
use Zngue\Module\Models\ModuleModels;
use Zngue\Module\Models\TemplateModel;
use Zngue\User\Service\ConstService;

class CodeService
{

    public static $ext_js='';
    /**
     * @param $keywords
     * @param $field
     * @param $order
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getList($keywords,$field,$order,$limit){

        $list=CodeModel::where(function ($q) use ($keywords){
            if (!empty($keywords)){
                $q->orWhere('name','like','%'.$keywords.'%');
            }
        });
        $list->leftJoin('namespace','code_generator.space_id','=','namespace.id');
        $list->select(['code_generator.*','namespace.name as n_name']);
        if ($field && $order){
            $list->orderBy($field,$order);
        }else{
            $list->orderBy('id','desc');
        }

        return $list->paginate(ConstService::pageNum($limit));
    }
    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model|CodeModel
     */
    public static function add($data){
        return CodeModel::create($data);
    }
    /**
     * @param $id
     * @param $data
     * @return int
     */
    public static function edit($id,$data){
        return CodeModel::where('id',$id)->update($data);
    }
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|
     * \Illuminate\Database\Eloquent\Model|
     * CodeModel|CodeModel[]|
     * null
     */
    public static function getOne($id){
        return CodeModel::find($id);
    }
    /**
     * @param $id
     * @return bool
     */
    public static function del($id){
        $data =self::getOne($id);
        if ($data){
            return $data->delete();
        }
        return false;
    }
    public static function changeStatus($data){
        return CodeModel::where('id',$data['id'])->update([$data['name']=>$data['status']]);
    }

    /**
     * @return array
     */
    public static function ajaxList(){
       $data =NameSpaceModel::select(['id','name'])->orderBy('sort','asc')->get();
       if ($data){
           return $data->toArray();
       }
        return [];
    }

    public static function codeCompete($id){
        $code = CodeModel::find($id);
        $namespace = NameSpaceModel::find($code->space_id);
        if (!$namespace || !$code){
            return false;
        }

        if ($code->finish==1){
            return 2;
        }
        $module = ModuleModels::find($code->mod_id);
        $field = FieldModel::with(['template'=>function( $q){
            $q->where('status',1);
        }])->where('mod_id',$module->id)->get();
        self::controllerCompete($code,$namespace);
        self::serviceCompete($code,$namespace);
        self::modelCompete($code,$namespace,$module,$field);
        self::tplCompete($code,$namespace,$field);
        self::requesCompete($code,$namespace,$field);

        if ($code->route_status==0){
            self::routeCompete($code,$namespace);
            self::readmeTpl($namespace,$code);
        }
        $code->finish=1;
        $code->save();
        return true;
    }
    public static function readmeTpl(NameSpaceModel $nameSpaceModel,CodeModel $codeModel){

        $address = $nameSpaceModel->address;
        $route_name= strtolower($codeModel->name);
        $path =base_path($address."README.md");
//        echo $path;die;
        $basePath  =   __DIR__.'/../../template/view/reamd.tpl';
        $temp =File::get($basePath);
        $temp = str_replace("{{route_name}}",$route_name, $temp);
        $temp = str_replace("{{name}}",$codeModel->title, $temp);
        file_put_contents($path, $temp, FILE_APPEND);
    }

    public static function requesCompete($code,$namespace,$field){

        self::addRequesCompete($code,$namespace,$field);
        self::saveRequesCompete($code,$namespace,$field);

    }
    public static function addRequesCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel,$field){

        $address = $nameSpaceModel->address;
        $name = ucwords($codeModel->name);
        $path =base_path($address."src/Http/Request/{$name}/");
        $basePath  =   __DIR__.'/../../template/request/add.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $valid=self::requestInfoTpl($field);
        $temp = str_replace("{{namespace}}", $nameSpaceModel->space, $temp);
        $temp = str_replace("{{name}}",ucwords($codeModel->name), $temp);
        $temp = str_replace("{{rules}}",$valid['rules'], $temp);
        $temp = str_replace("{{message}}",$valid['message'], $temp);
        File::put($path.'AddRequest.php',$temp);
    }
    public static function requestInfoTpl($field){

        $rules = '';
        $message='';
        if ($field){
            $info  = $field->toArray();
            $rules_arr=[];
            $arr=[];
            foreach ($info as $v){
                if ($v['verify_from']){
                    $rules_arr[]="'{$v['name']}'=>'{$v['verify_from']}'";
                    $info_arr_message=explode("|",$v['verify_from']);
                    foreach ($info_arr_message as $vs){
                        $arr[]="'{$v['name']}.{$vs}'=>':attribute {$vs} {$v['name_alias']}' ";
                    }
                }
            }
            if (!empty($rules_arr)){
                $rules=implode(',',$rules_arr);
            }
            if (!empty($arr)){
                $message = implode(',',$arr);
            }
        }
        return ['rules'=>$rules,'message'=>$message];
    }

    public static function saveRequesCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel,$field){
        $address = $nameSpaceModel->address;
        $name = ucwords($codeModel->name);
        $path =base_path($address."src/Http/Request/{$name}/");
        $basePath  =   __DIR__.'/../../template/request/save.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $valid=self::requestInfoTpl($field);
        $temp = str_replace("{{namespace}}", $nameSpaceModel->space, $temp);
        $temp = str_replace("{{name}}",ucwords($codeModel->name), $temp);
        $temp = str_replace("{{rules}}",$valid['rules'], $temp);
        $temp = str_replace("{{message}}",$valid['message'], $temp);
        File::put($path.'SaveRequest.php',$temp);
    }

    public static function routeCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel){
        $address = $nameSpaceModel->address;
        $route_name= strtolower($codeModel->name);
        $path =base_path($address."routes/resources.php");
        $basePath  =   __DIR__.'/../../template/route.tpl';
        $temp =File::get($basePath);
        $temp = str_replace("{{route_name}}",$route_name, $temp);
        $temp = str_replace("{{name}}",ucwords($codeModel->name), $temp);
       $res = file_put_contents($path, $temp, FILE_APPEND);
       if ($res){
           $codeModel->route_status=1;
           $codeModel->save();
       }
    }
    public static function controllerCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel){
        $address = $nameSpaceModel->address;
        $path =base_path($address.'src/Http/Controller/');
        $basePath  =   __DIR__.'/../../template/controller.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $temp = str_replace("{{namespace}}", $nameSpaceModel->space, $temp);
        $temp = str_replace("{{name}}",ucwords($codeModel->name), $temp);
        $temp = str_replace("{{route}}",strtolower($codeModel->name), $temp);
        File::put($path.ucwords($codeModel->name).'Controller.php',$temp);
    }
    public static function serviceCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel){
        $address = $nameSpaceModel->address;
        $path =base_path($address.'src/Service/');
        $basePath  =   __DIR__.'/../../template/service.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $temp = str_replace("{{namespace}}", $nameSpaceModel->space, $temp);
        $temp = str_replace("{{name}}",ucwords($codeModel->name), $temp);
        $temp = str_replace("{{service}}",ucwords($codeModel->name), $temp);
        File::put($path.ucwords($codeModel->name).'Service.php',$temp);
    }
    public static function modelField($model){
        $return_data=[
            'node_tr'  =>'',
            'fillable_str'=>''
        ];
        if ($model){
            $field=   $data = $model->toArray();
            $node_tr = '';
            $fillable_str='';
            foreach ($field as $v){
                $name = $v['name'];
                $node_tr.='*@property $'.$name."\n";
                $fillable_str.="'{$name}',";
            }
            $newstr = substr($fillable_str,0,strlen($fillable_str)-1);
            $return_data=[
                'node_tr'=>$node_tr,
                'fillable_str'=>$newstr
            ];
        }
        return $return_data;
    }
    public static function modelCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel,ModuleModels $models, $field){
        $address = $nameSpaceModel->address;
        $path =base_path($address.'src/Models/');
        $basePath  =   __DIR__.'/../../template/model.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $table_name = $models->name;//表名
        $field_arr=self::modelField($field);
        $temp = str_replace("{{namespace}}", $nameSpaceModel->space, $temp);
        $temp = str_replace("{{name}}",ucwords($codeModel->name), $temp);
        $temp = str_replace("{{table_name}}",$table_name, $temp);
        $temp = str_replace("{{notes}}",$field_arr['node_tr'], $temp);
        $temp = str_replace("{{fillable}}",$field_arr['fillable_str'], $temp);
        File::put($path.ucwords($codeModel->name).'Model.php',$temp);
    }
    public static function tplCompete($code ,$namespace,$field){
        self::indexTplCompete($code,$namespace);
        self::saveTplCompete($code,$namespace,$field);
        self::addTplCompete($code,$namespace,$field);
        self::jsTplCompete($code,$namespace,$field);
    }
    public static function indexTplCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel){
        $address = $nameSpaceModel->address;
        $view_name= strtolower($codeModel->name);
        $path =base_path($address."views/{$view_name}/");
        $basePath  =   __DIR__.'/../../template/view/index.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $temp = str_replace("{{name}}",$view_name, $temp);
        File::put($path.'index.blade.php',$temp);
    }
    public static function jsTplCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel,$field)
    {
        $address = $nameSpaceModel->address;
        $view_name = strtolower($codeModel->name);
        $path = base_path($address . "views/{$view_name}/");
        $basePath = __DIR__ . '/../../template/view/js.tpl';
        $temp = File::get($basePath);
        if (!File::exists($path)) {
            File::makeDirectory($path, 775);
        }
        $temp_conten='';
        if ($field) {
            foreach ($field->toArray() as $v) {
                if ($v['is_show_list']==1){
                    $temp_one = $v['template']['js_content'];
                    $temp_one = str_replace("{{name}}",$v['name'], $temp_one);
                    $temp_conten .= str_replace("{{name_alias}}",$v['name_alias'], $temp_one);
                }
            }
            $temp = str_replace("{{temp_list}}",$temp_conten, $temp);
        }

        $temp = str_replace("{{name}}",$view_name, $temp);
        $temp = str_replace("{{title}}",$codeModel->title, $temp);
        $temp = str_replace("{{ext_js}}",self::$ext_js, $temp);
        File::put($path.'js.blade.php',$temp);
    }
    public static function saveTplCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel,$field){
        $address = $nameSpaceModel->address;
        $view_name= strtolower($codeModel->name);
        $path =base_path($address."views/{$view_name}/");
        $basePath  =   __DIR__.'/../../template/view/save.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $temp_conten=self::editTplCompete($field,0);
        $temp = str_replace(" {{temp}}",$temp_conten, $temp);
        $temp = str_replace("{{name}}",$view_name, $temp);
        File::put($path.'save.blade.php',$temp);
    }
    public static function editTplCompete($field,$type=1){
        $temp_conten='';
        if ($field){
            foreach ($field->toArray() as $v){
                $temp_str='{{$data["'.$v['name'].'"]}}';
                $temp_one=$v['template']['edit_content'];
                $verify = !empty($v['verify'])?$v['verify']:'';
                $temp_one = str_replace("{{name_alias}}",$v['name_alias'], $temp_one);
                if ($type==0){
                    $temp_one = str_replace("{{value}}",$temp_str, $temp_one);
                }else{
                    $temp_one = str_replace("{{value}}",'', $temp_one);
                }
                $temp_one = str_replace("{{verify}}",$verify, $temp_one);
                $temp_conten.= str_replace("{{name}}",$v['name'], $temp_one).PHP_EOL;
//                print_r($v);die;
                if (!empty($v['template']['ex_js_content']) && $type==1 ){
                    $temp_ext_js=str_replace("{{name}}",$v['name'], $v['template']['ex_js_content']);
                    $temp_ext_js=str_replace("{{default}}",$v['default'], $temp_ext_js);
                    self::$ext_js.=str_replace("{{verify}}",$v['verify'], $temp_ext_js).PHP_EOL;
                }
            }
        }
        return $temp_conten;
    }
    public static function addTplCompete(CodeModel $codeModel,NameSpaceModel $nameSpaceModel,  $field){
        $address = $nameSpaceModel->address;
        $view_name= strtolower($codeModel->name);
        $path =base_path($address."views/{$view_name}/");
        $basePath  =   __DIR__.'/../../template/view/add.tpl';
        $temp =File::get($basePath);
        if(!File::exists($path)){
            File::makeDirectory($path,775);
        }
        $temp_conten=self::editTplCompete($field);
        $temp = str_replace(" {{temp}}",$temp_conten, $temp);
        $temp = str_replace("{{name}}",$view_name, $temp);
        File::put($path.'add.blade.php',$temp);
    }
    public static function getModelList(){
        $model =ModuleModels::where('status',1)->select(['title as name','id'])->get();
        if ($model){
            return $model->toArray();
        }else{
            return [];
        }
    }

}
