<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('crudly.route_prefix'),
    'middleware' => config('crudly.middleware'),
], function () {
    Route::get('/check', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Crudly is working!',
            'version' => '1.0.0',
        ]);
    });
});
