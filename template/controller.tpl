<?php
namespace {{namespace}}Http\Controller;
use Illuminate\Http\Request;
use {{namespace}}Service\{{name}}Service;
use Zngue\Module\Http\Request\Module\ChangeRequest;
use Zngue\User\Http\Controller\Controller;
use {{namespace}}Http\Request\{{name}}\AddRequest;
use {{namespace}}Http\Request\{{name}}\SaveRequest;
class {{name}}Controller extends Controller
{
    const route_base='{{route}}.';
    public function index(Request $request ){
        if ($request->ajax() ){
            $keywords=$request->input('keywords','');
            $field=$request->input('field','');
            $order=$request->input('order','');
            $limit = $request->input('limit','');
            $listObj ={{name}}Service::getList($keywords,$field,$order,$limit);
            $item =$listObj->items();
            $num =$listObj->total();
            return $this->layTableJson($item,$num);
        }
        return $this->zngView(self::route_base.'index');
    }
    public function add(){
        return $this->zngView(self::route_base.'add');

    }
    public function doAdd(AddRequest $request){
        $data =$request->all();
//        print_r($data);die;
        $res ={{name}}Service::add($data);
        return $this->returnInfo($res);
    }
    public function save(Request $request){
        $id = $request->input('id',0);
        if (!$id){
            return redirect()->route(self::route_base.'index');
        }
        $data={{name}}Service::getOne($id)->toArray();
        return $this->zngView(self::route_base.'save',compact('data'));

    }
    public function doSave(SaveRequest $request){
        $data =$request->all();
        $r={{name}}Service::edit($data['id'],$data);
        return $this->returnInfo($r);
    }
    public function del(Request $request){
        $id =$request->input('id',0);
        $r={{name}}Service::del($id);
        return $this->returnInfo($r);
    }
    public function changeStatus(ChangeRequest $request){
        $data =$request->only('id','name','status');
        $r ={{name}}Service::changeStatus($data);
        return $this->returnInfo($r);
    }
    public function delAll(){


    }

}
