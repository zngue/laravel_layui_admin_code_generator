<?php


namespace Zngue\CodeGenerator\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zngue\CodeGenerator\Service\CodeService;
use Zngue\Module\Http\Request\Module\ChangeRequest;
use Zngue\User\Http\Controller\Controller;
class CodeController extends Controller
{

    const route_base='code.';
    public function index(Request $request ){
        if ($request->ajax() ){
            $keywords=$request->input('keywords','');
            $field=$request->input('field','');
            $order=$request->input('order','');
            $limit = $request->input('limit','');
            $listObj =CodeService::getList($keywords,$field,$order,$limit);
            $item =$listObj->items();
            $num =$listObj->total();
            return $this->layTableJson($item,$num);
        }
        return $this->zngView(self::route_base.'index');
    }
    public function add(){
        return $this->zngView(self::route_base.'add');

    }
    public function doAdd(Request $request){
        $data =$request->all();
//        print_r($data);die;
        $res =CodeService::add($data);
        return $this->returnInfo($res);
    }
    public function save(Request $request){
        $id = $request->input('id',0);
        if (!$id){
            return redirect()->route(self::route_base.'index');
        }
        $data=CodeService::getOne($id)->toArray();
        return $this->zngView(self::route_base.'save',compact('data'));

    }
    public function doSave(Request $request){
        $data =$request->all();
        $r=CodeService::edit($data['id'],$data);
       // print_r($r);die;
        return $this->returnInfo($r);
    }
    public function del(Request $request){
        $id =$request->input('id',0);
        $r=CodeService::del($id);
        return $this->returnInfo($r);
    }
    public function changeStatus(ChangeRequest $request){
        $data =$request->only('id','name','status');
        $r =CodeService::changeStatus($data);
        return $this->returnInfo($r);
    }
    public function delAll(){


    }

    /**
     * @return array
     */
    public function ajaxList(){
        return CodeService::ajaxList();
    }
    public function completeCode(Request $request){
        $id = $request->input('id',0);
        $r =CodeService::codeCompete($id);
        if ($r===2){
            return $this->returnError('您已生成请修改状态重新生成,重新生成请注意删除多余的路由文件');
        }
        return $this->returnInfo($r);
    }
    public function moudel(){
        return CodeService::getModelList();
    }

}
