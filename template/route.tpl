/**
{{route_name}}.index  列表
{{route_name}}.del  删除
{{route_name}}.add|{{route_name}}.doAdd  添加
{{route_name}}.save|{{route_name}}.doSave|{{route_name}}.status  修改*/
Route::prefix('{{route_name}}')->name('{{route_name}}.')->group(function (){
    Route::get('index','{{name}}Controller@index')->name('index');
    Route::post('del','{{name}}Controller@del')->name('del');
    Route::get('add','{{name}}Controller@add')->name('add');
    Route::post('doAdd','{{name}}Controller@doAdd')->name('doAdd');
    Route::post('status','{{name}}Controller@changeStatus')->name('status');
    Route::get('save','{{name}}Controller@save')->name('save');
    Route::post('doSave','{{name}}Controller@doSave')->name('doSave');
    Route::post('delAll',"{{name}}Controller@delAll")->name('delAll');
});

