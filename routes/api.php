<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

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

Route::post('employee-login', [ApiController::class, 'EmployeeLogin']);
Route::post('mark-attendance', [ApiController::class, 'MarkAttendance']);
Route::post('save-contact', [ApiController::class, 'SaveContact']);
Route::post('get-emp', [ApiController::class, 'GetEmp']);
Route::get('get-emp-decrypt/{$url}', [ApiController::class, 'GetEmpDecrypt']);
Route::post('fetch-requirement-details', [ApiController::class, 'FetchRequirementDetails']);

Route::any('today-attendance', [ApiController::class, 'TodayAttendance']);
Route::any('attendance-history', [ApiController::class, 'AttendanceHistory']);

Route::any('user-profile', [ApiController::class, 'UserProfile']);
Route::any('update-profile', [ApiController::class, 'updateProfile']);











