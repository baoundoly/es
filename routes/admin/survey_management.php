<?php
// survey
Route::prefix( 'survey-info' )->name( 'survey-info.' )->group( function () {
    Route::get( '/list', 'SurveyController@index' )->name( 'list' );
    Route::get( '/add', 'SurveyController@add' )->name( 'add' );
    Route::post( '/store', 'SurveyController@store' )->name( 'store' );
    Route::get( '/edit/{editData}', 'SurveyController@edit' )->name( 'edit' );
    Route::post( '/update/{editData}', 'SurveyController@update' )->name( 'update' );
    Route::post( '/destroy', 'SurveyController@destroy' )->name( 'destroy' );
    Route::get( '/export', 'SurveyController@export' )->name( 'export' );

    Route::get('get-voter-info', 'SurveyController@getVoterInfo')->name('get-voter-info');
});
