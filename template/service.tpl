<?php
namespace {{namespace}}Service;
use {{namespace}}Models\{{name}}Model;
use Zngue\User\Service\ConstService;
class {{name}}Service
{
    /**
     * @param $keywords
     * @param $field
     * @param $order
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getList($keywords,$field,$order,$limit){

        $list={{name}}Model::where(function ($q) use ($keywords){
            if (!empty($keywords)){
                $q->orWhere('name','like','%'.$keywords.'%');
            }
        });
        if ($field && $order){
            $list->orderBy($field,$order);
        }
        return $list->paginate(ConstService::pageNum($limit));
    }
    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model|{{name}}Model
     */
    public static function add($data){
        return {{name}}Model::create($data);
    }
    /**
     * @param $id
     * @param $data
     * @return int
     */
    public static function edit($id,$data){

        return {{name}}Model::where('id',$id)->update($data);
    }
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getOne($id){
        return {{name}}Model::find($id);
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
        return {{name}}Model::where('id',$data['id'])->update([$data['name']=>$data['status']]);
    }

}
