<?php


use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Comentario;
use App\Models\Conteudo;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConteudoController;

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
Route::middleware('auth:api')->post('/conteudo/adicionar',[ConteudoController::class,'adicionar']);
Route::middleware('auth:api')->get('/conteudo/listar',[ConteudoController::class,'listar']);
Route::middleware('auth:api')->put('/conteudo/curtir/{id}',[ConteudoController::class,'curtir']);

Route::get('/testes',function(){
    $user = User::find(1);
    $user2 = User::find(2);

    $conteudos = Conteudo::all();
    foreach($conteudos as $key => $value){
        $value->delete();
    }
    //Adicionar conteudo
    // $user->conteudos()->create([
    // 'titulo'=>'Conteudo 2',
    // 'texto'=>'Aqui o texto 2',
    // 'imagem'=>'url da imagem 2',
    // 'link'=>'link 2',
    // 'data'=>'2021-03-19'
    // ]);
    // return $user->conteudos;

    //Adicionar amigos
    // $user->amigos()->attach($user2->id);
    // $user->amigos()->detach($user2->id);
    // $user->amigos()->toggle($user2->id);

    //return $user->amigos;

    //Adicionar curtidas
    // $conteudo = Conteudo::find(1);
    // $user->curtidas()->toggle($conteudo->id);
    // $conteudo->curtidas()->count();

    // return $conteudo->curtidas;

    //Adicionar comentrios
    // $conteudo = Conteudo::find(1);
    // $user->comentarios()->create([
    //     'conteudo_id'=>$conteudo->id,
    //     'texto'=>'Gostei da foto',
    //     'data'=>date('Y-m-d')
    // ]);
    // return $conteudo->comentarios;
});


