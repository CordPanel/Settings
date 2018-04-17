<?php

/*
|--------------------------------------------------------------------------
| Cord\Settings Routes
|--------------------------------------------------------------------------
| To ensure consistancy of routing we use config: Backpack.Base.Route_Prefix
| for a route prefix for the administration panel.
*/

Route::group([
    'namespace'  => 'Cord\Settings\app\Http\Controllers',
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    Route::get('setting/system', 'SystemConfigController@index');
    Route::get('setting/system/search/{file}', 'SystemConfigController@search');

    CRUD::resource('setting/app', 'SettingCrudController');
});
