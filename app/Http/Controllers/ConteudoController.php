<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conteudo;
use Illuminate\Support\Facades\Validator;
class ConteudoController extends Controller
{
    public function listar()
    {
        $conteudos = Conteudo::with('user')->orderBy('data','DESC')->paginate(5);

        return ['status'=>true,'conteudos'=>$conteudos];

    }

    public function adicionar(Request $request)
    {
        $data= $request->all();
        $user = $request->user();

        //Validação
        $validacao = Validator::make($data, [
            'titulo' => 'required',
            'texto' => 'required',
        ]);
        //Verificando se há erro na requisição    
        if ($validacao->fails()) {
            return ['status'=>false,"validacao"=>true,"erros"=>$validacao->errors()];
        }

        $conteudo = new Conteudo;

        $conteudo->titulo = $data['titulo'];
        $conteudo->texto = $data['texto'];
        $conteudo->link = $data['link'] ? $data['link'] : '#';
        $conteudo->imagem = $data['imagem'] ? $data['imagem'] : '#';
        $conteudo->data = date('Y-m-d H:i:s');

        $user->conteudos()->save($conteudo);
        return ['status'=>true,'conteudos'=>$conteudo];
    }
    public function curtir($id,Request $request){
        
        $conteudo = Conteudo::find($id);
        if($conteudo){
            $user = $request->user();
            $user->curtidas()->toggle($conteudo->id);
            // $conteudo->curtidas()->count();
            return['status'=>true,'curtidas'=>$conteudo->curtidas()->count()];
        }else{
            return['status'=>false,'erro'=>'Conteúdo não existe!'];
        }
    }
}
