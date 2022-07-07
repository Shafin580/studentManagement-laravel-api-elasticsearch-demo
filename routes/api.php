<?php

use App\Http\Controllers\ClassCoursePivotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WeekDayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Route::post('/student/entry', [StudentController::class, 'store']);
Route::prefix('/class/course')->group(function () {
    Route::get('/', [ClassCoursePivotController::class, 'index']);
    Route::post('/entry', [ClassCoursePivotController::class, 'store']);
    Route::get('/{classcourse}', [ClassCoursePivotController::class, 'show']);
    Route::post('/{classcourse}', [ClassCoursePivotController::class, 'update']);
});

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    Route::get('/course/{course}', [CourseController::class, 'show']);

    //Route::post('/login', [PassportAuthController::class, 'login']);
});

Route::post('/login', [PassportAuthController::class, 'login']);

Route::prefix('/course')->group(function () {
    Route::get('/{course}', [CourseController::class, 'show']);
    Route::get('/', [CourseController::class, 'index']);
});

Route::prefix('/class')->group(function () {
    Route::get('/{class}', [ClassRoomController::class, 'show']);
    Route::get('/', [ClassRoomController::class, 'index']);
});

Route::prefix('/weekday')->group(function () {
    Route::get('/{weekday}', [WeekDayController::class, 'show']);
    Route::get('/', [WeekDayController::class, 'index']);
});

Route::prefix('/student')->group(function () {
    Route::get('/', [StudentController::class, 'index']);
    Route::get('/{student}', [StudentController::class, 'show']);
    Route::post('/test/test', [StudentController::class, 'test']);
    Route::post('/{student}', [StudentController::class, 'update']);
    Route::post('/entry', [StudentController::class, 'store']);
});

Route::prefix('/routine')->group(function () {
    Route::get('/', [RoutineController::class, 'index']);
    Route::get('/{routine}', [RoutineController::class, 'show']);
    Route::post('/entry', [RoutineController::class, 'store']);
    Route::delete('/delete/{routine}', [RoutineController::class, 'destroy']);
});

