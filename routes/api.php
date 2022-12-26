<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;



Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    
   

    Route::group([
        'middleware' => [
            'permission:admin'
            ]
        ], function () {
        
        Route::get('/documents', [DocumentController::class, 'documents']);
        Route::post('/send-document/{to_user_id}', [DocumentController::class, 'send_document']);

    }); 



    
});
