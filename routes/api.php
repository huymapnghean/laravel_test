<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketLabelController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ManageTicketController;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    Route::get('labels', [TicketLabelController::class, 'getAll']);
    Route::get('categories', [TicketCategoryController::class, 'getAll']);

    Route::prefix('tickets')->group(function () {
        Route::get('',[TicketController::class, 'listTicket']);
        Route::get('/{id}',[TicketController::class, 'getTicketByID'])->name('ticket.show');
        Route::post('',[TicketController::class, 'createTicket'])->middleware('role:' . UserDetail::USER);
        Route::put('/{id}',[TicketController::class, 'updateTicket'])->middleware('role:' . UserDetail::AGENT .','.UserDetail::ADMIN);
        Route::delete('/{id}',[TicketController::class, 'deleteTicket'])->middleware('role:' . UserDetail::ADMIN);
    });

    Route::prefix('admin')->group(function () {
        Route::post('',[ManageTicketController::class, 'updateManageTicket'])->middleware('role:' . UserDetail::ADMIN);
        Route::post('/assign',[TicketController::class, 'updateAgentTicket'])->middleware('role:' . UserDetail::ADMIN);
    });
});
