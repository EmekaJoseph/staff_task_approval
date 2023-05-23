<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;



Route::controller(AuthController::class)->group(function () {
    Route::post('user/create',  'userCreate');
    Route::post('user/login',  'userLogin');
});


//  ######################## PROTECTED ROUTES WITH SANCTUM ########################## //
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('user/logout', [AuthController::class, 'userLogout']);

    Route::controller(TaskController::class)->prefix('task')->group(function () {
        // staff
        Route::post('/create',  'taskCreate');
        Route::get('/list',  'taskList');
        Route::get('/delete/{task_id}',  'taskDelete');
        Route::get('/mark_as_complete/{task_id}',  'taskMarkComplete');
        // approver
        Route::get('/completed_list',  'taskCompletedList');
        Route::get('/approve/{task_id}',  'taskApprove');
    });
});
