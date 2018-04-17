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

    CRUD::resource('setting', 'SettingCrudController');


    Route::get('config', function() {

      $config = new Cord\Settings\Repository('backpack.base'); // loading the config from config/app.php
      $config->set('developer_name', 'Cord Panel'); // set the config you wish
      $config->save(); // save those settings to the config file once done editing

      dd($config->get('developer_name'));
    });
});
