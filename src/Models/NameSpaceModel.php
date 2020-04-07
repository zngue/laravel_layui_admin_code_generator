<?php


namespace Zngue\CodeGenerator\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 * @property  $id
 * @property $name
 * @property $address
 * @property $space
 * @property $created_at
 * @property $updated_at
 * @property $status
 * Class NameSpaceModel
 * @package Zngue\CodeGenerator\Models
 */
class NameSpaceModel extends Model
{

    protected $table='namespace';
    protected $fillable=[
        'address','name',  'space', 'status'
    ];


}
