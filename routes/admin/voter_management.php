<?php
// Voter
Route::prefix( 'voter-info' )->name( 'voter-info.' )->group( function () {
    Route::get( '/list', 'VoterController@index' )->name( 'list' );
    Route::get( '/add', 'VoterController@add' )->name( 'add' );
    Route::post( '/store', 'VoterController@store' )->name( 'store' );
    Route::get( '/edit/{editData}', 'VoterController@edit' )->name( 'edit' );
    Route::post( '/update/{editData}', 'VoterController@update' )->name( 'update' );
    Route::post( '/destroy', 'VoterController@destroy' )->name( 'destroy' );
});
