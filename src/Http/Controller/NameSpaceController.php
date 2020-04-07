<?php
namespace Zngue\CodeGenerator\Http\Controller;
use Illuminate\Http\Request;
use Zngue\CodeGenerator\Service\NameSpaceService;
use Zngue\Module\Http\Request\Module\ChangeRequest;
use Zngue\User\Http\Controller\Controller;
class NameSpaceController extends Controller
{
    const route_base='namespace.';
    public function index(Request $request ){
        if ($request->ajax() ){
            $keywords=$request->input('keywords','');
            $field=$request->input('field','');
            $order=$request->input('order','');
            $limit = $request->input('limit','');
            $listObj =NameSpaceService::getList($keywords,$field,$order,$limit);
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
        $res =NameSpaceService::add($data);
        return $this->returnInfo($res);
    }
    public function save(Request $request){
        $id = $request->input('id',0);
        if (!$id){
            return redirect()->route(self::route_base.'index');
        }
        $data=NameSpaceService::getOne($id)->toArray();
        return $this->zngView(self::route_base.'save',compact('data'));

    }
    public function doSave(Request $request){
        $data =$request->all();
        $r=NameSpaceService::edit($data['id'],$data);
        return $this->returnInfo($r);
    }
    public function del(Request $request){
        $id =$request->input('id',0);
        $r=NameSpaceService::del($id);
        return $this->returnInfo($r);
    }
    public function changeStatus(ChangeRequest $request){
        $data =$request->only('id','name','status');
        $r =NameSpaceService::changeStatus($data);
        return $this->returnInfo($r);
    }
    public function delAll(){


    }

}
