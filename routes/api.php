<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VoterController;
use App\Http\Controllers\Api\SurveyController;
use App\Http\Controllers\Api\ResultController;

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

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Voter API routes (protected with sanctum auth)
Route::middleware('auth:sanctum')->group(function () {
    // Get all ward numbers
    Route::get('/wards', [VoterController::class, 'getAllWards']);
    
    // Get voters by ward
    Route::get('/voters', [VoterController::class, 'getVotersByWard']);
    
    // Get single voter by ID
    Route::get('/voters/{id}', [VoterController::class, 'getVoterById']);
    
    // Search voter by voter_no
    Route::get('/voters/search/by-number', [VoterController::class, 'searchVoterByNo']);

    // Search voters by ward and address (partial match)
    Route::get('/voters/search/by-address', [VoterController::class, 'getVotersByWardAndAddress']);

    // Address suggestions (word-wise)
    Route::get('/addresses', [VoterController::class, 'getAddressSuggestions']);

    // Update cant_access field for voters by ward and address
    Route::post('/voters/update-cant-access', [VoterController::class, 'updateCantAccess']);

    // Update voter ward and address by matching ward and exact address
    Route::post('/voters/update-address', [VoterController::class, 'updateAddress']);

    // Survey API routes
    Route::post('/surveys', [SurveyController::class, 'store']);
    Route::get('/surveys', [SurveyController::class, 'index']);
    Route::get('/surveys/{id}', [SurveyController::class, 'show']);
    Route::put('/surveys/{id}', [SurveyController::class, 'update']);
    Route::delete('/surveys/{id}', [SurveyController::class, 'destroy']);

    // Result list
    Route::get('/results', [ResultController::class, 'index']);
});
