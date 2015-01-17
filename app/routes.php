<?php


Route::group(['prefix' => 'quotes'], function()
{
    Route::get('/', [
        'as'    => 'quote_path',
        'uses'  => 'QuoteController@index'
    ]);

    Route::get('/add', [
        'as'    => 'add_quote_path',
        'uses'  => 'QuoteController@create'
    ]);
});