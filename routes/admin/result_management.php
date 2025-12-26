<?php
// result
Route::prefix( 'result-info' )->name( 'result-info.' )->group( function () {
    Route::get( '/list', 'ResultController@index' )->name( 'list' );
    Route::get( '/add', 'ResultController@add' )->name( 'add' );
    Route::post( '/store', 'ResultController@store' )->name( 'store' );
    Route::get( '/edit/{editData}', 'ResultController@edit' )->name( 'edit' );
    Route::post( '/update/{editData}', 'ResultController@update' )->name( 'update' );
    Route::post( '/destroy', 'ResultController@destroy' )->name( 'destroy' );
});
