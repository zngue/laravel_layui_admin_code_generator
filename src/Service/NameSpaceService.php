<?php
namespace Zngue\CodeGenerator\Service;
use Zngue\CodeGenerator\Models\NameSpaceModel;
use Zngue\User\Service\ConstService;

class NameSpaceService
{
    /**
     * @param $keywords
     * @param $field
     * @param $order
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getList($keywords,$field,$order,$limit){

        $list=NameSpaceModel::where(function ($q) use ($keywords){
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
     * @return \Illuminate\Database\Eloquent\Model|NameSpaceModel
     */
    public static function add($data){
        return NameSpaceModel::create($data);
    }
    /**
     * @param $id
     * @param $data
     * @return int
     */
    public static function edit($id,$data){

        return NameSpaceModel::where('id',$id)->update($data);
    }
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|
     * \Illuminate\Database\Eloquent\Model|
     * NameSpaceModel|NameSpaceModel[]|
     * null
     */
    public static function getOne($id){
        return NameSpaceModel::find($id);
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
        return NameSpaceModel::where('id',$data['id'])->update([$data['name']=>$data['status']]);
    }

}
