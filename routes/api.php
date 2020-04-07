<?php
Route::prefix('namespace')->name('namespace.')->group(function (){
    Route::get('index','NameSpaceController@index')->name('index');
    Route::post('del','NameSpaceController@del')->name('del');
    Route::get('add','NameSpaceController@add')->name('add');
    Route::post('doAdd','NameSpaceController@doAdd')->name('doAdd');
    Route::post('status','NameSpaceController@changeStatus')->name('status');
    Route::get('save','NameSpaceController@save')->name('save');
    Route::post('doSave','NameSpaceController@doSave')->name('doSave');
    Route::post('delAll',"NameSpaceController@delAll")->name('delAll');
});
Route::prefix('code')->name('code.')->group(function (){
    Route::get('index','CodeController@index')->name('index');
    Route::post('del','CodeController@del')->name('del');
    Route::get('add','CodeController@add')->name('add');
    Route::post('doAdd','CodeController@doAdd')->name('doAdd');
    Route::post('status','CodeController@changeStatus')->name('status');
    Route::get('save','CodeController@save')->name('save');
    Route::post('doSave','CodeController@doSave')->name('doSave');
    Route::post('delAll',"CodeController@delAll")->name('delAll');
    Route::get('ajaxList',"CodeController@ajaxList")->name('ajaxList');
    Route::get('completeCode',"CodeController@completeCode")->name('completeCode');
});
