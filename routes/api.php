<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\forgotPasswordController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ModuleClientController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GammeController;

use App\Models\ModuleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post("/login", [AuthController::class, "login"]);
Route::get("/logout", [AuthController::class, "logout"]);
Route::get('asks', [InterventionController::class, 'allAskInterventions']);
Route::post('/askIntervention', [InterventionController::class, 'askIntervention']);
Route::put('/assignIntervention/{interventionId}/assign/{userId}', [InterventionController::class, 'asignIntervention']);
Route::put('/fiche/{interventionId}', [InterventionController::class, 'ficheIntervention']);
Route::get('/fiches', [InterventionController::class, 'allFiches']);
Route::get("/myFiche/{id}", [InterventionController::class, 'showFiche']);
Route::apiResource('/modules', ModuleController::class);
Route::apiResource('/user', UserController::class);
Route::get('/clients', [UserController::class, 'allClients']);
//route
Route::get('/users', [UserController::class, 'allUsers']);
Route::post('/users', [UserController::class, "store"]);
Route::apiResource('/rapport', RapportController::class);
Route::apiResource('/interventions', InterventionController::class);
Route::apiResource('/notes', NoteController::class);
// Route::put('/cloture/{id}', [InterventionController::class, 'clotured']);
Route::post('/forgot', [ForgotPasswordController::class, 'forgot']);
Route::post('/reset', [ForgotPasswordController::class, 'reset']);
Route::post('/insert', [UserController::class, 'insertData']);
Route::put('/update/{user}', [UserController::class, 'updateData']);
Route::apiResource('/moduleClient', ModuleClientController::class);

Route::apiResource('/gamme', GammeController::class);

