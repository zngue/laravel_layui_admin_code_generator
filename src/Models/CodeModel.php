<?php


namespace Zngue\CodeGenerator\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property $id
 * @property $name
 * @property $title
 * @property $sort
 * @property $status
 * @property $space_id
 * @property $created_at
 * @property $updated_at
 * @property $finish
 * @property $mod_id
 * Class CodeModel
 * @package Zngue\CodeGenerator\Models
 */
class CodeModel extends Model
{
    protected $table='code_generator';
    protected $fillable=[
        'name','title','sort','status','space_id','finish','mod_id'
    ];
}
