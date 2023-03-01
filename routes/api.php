<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\IncomingController;



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

        //Filters
        Route::get('/cities/filter', [DocumentController::class, 'filter_cities']);

        Route::get('/user/profile', [AuthController::class, 'userProfile']);

        //Outgoing massages
        Route::get('/check-document', [DocumentController::class, 'check_document']);
        Route::get('/outgoing/documents', [DocumentController::class, 'outgoing_messages']);
        Route::get('/send-document/{document_id}', [DocumentController::class, 'send_document_ID']);
        Route::post('/send-document/preparing/{to_user_id}', [DocumentController::class, 'send_document_post']);
        Route::put('/send-document/status/{document}', [DocumentController::class, 'end_document']);

        Route::get('/add-worker-to-document', [DocumentController::class, 'add_worker_to_document_GET']);
        Route::post('/add-worker-to-document', [DocumentController::class, 'add_worker_to_document_POST']);
        Route::get('/worker/{worker_id}', [DocumentController::class, 'update_worker_GET']);
        Route::put('/worker/{worker}/update', [DocumentController::class, 'update_worker_POST']);
        Route::delete('/worker/{worker}/delete', [DocumentController::class, 'delete_worker']);

        
        Route::get('/worker/careers/{worker}', [WorkerController::class, 'careers']);
        Route::post('/worker/careers/create', [WorkerController::class, 'create_careers']);
        Route::put('/worker/careers/{career}/update', [WorkerController::class, 'update_careers']);
        Route::delete('/worker/careers/{career}/delete', [WorkerController::class, 'delete_careers']);

        
        Route::get('/worker/relatives/{worker}', [WorkerController::class, 'relatives']);
        Route::post('/worker/relatives/create', [WorkerController::class, 'create_relative']);
        Route::put('/worker/relatives/{worker_relative}/update', [WorkerController::class, 'update_relative']);
        Route::delete('/worker/relatives/{worker_relative}/delete', [WorkerController::class, 'delete_relative']);

        //Incoming massages
        Route::get('/incoming/documents', [IncomingController::class, 'incoming_messages']);

        
        
        Route::get('/admin/migrate', function () {
            Schema::disableForeignKeyConstraints();
            Artisan::call('migrate --force');
            Schema::enableForeignKeyConstraints();
            return true;
        });

    }); 
    
});
