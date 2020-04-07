<?php
namespace {{namespace}}Models;
use Illuminate\Database\Eloquent\Model;
/**
* @mixin \Eloquent
{{notes}}
 */
class {{name}}Model extends Model
{
    protected $table='{{table_name}}';
    protected $fillable = [{{fillable}}];
}
