<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\UsuarioController;

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

Route::post('/login',[UsuarioController::class,'login']);

Route::post('/cadastro',[UsuarioController::class,'cadastro']);

Route::middleware('auth:api')->put('/perfil',[UsuarioController::class,'perfil']);

Route::middleware('auth:api')->get('/usuario',[UsuarioController::class,'usuario']);


