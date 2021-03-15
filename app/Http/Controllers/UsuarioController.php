<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function login(Request $req)
    {
        $data = $req->all();

        //Validação da requisição
        $validacao = Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string'
        ]);
        //Verificando se há erro na requisição    
        if ($validacao->fails()) {
            return $validacao->errors();
        }
        //Se estiver tudo certo com a requisição
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = auth()->user();
            $user->token = $user->createToken($user->email)->accessToken;
            $user->imagem = asset($user->imagem);
            return $user;
        }
        //Se tiver algo errado com a requisição
        else {
            return ['status' => false];
        }
    }
    public function cadastro(Request $req)
    {
        $data = $req->all();
        //Validação da requisição
        $validacao = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
        //Verificando se há erro na requisição
        if ($validacao->fails()) {
            return $validacao->errors();
        }

        $imagem = "padrao/no-photo.jpg";

        //Cria o usuário com base na requisição
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'imagem' => $imagem

        ]);
        //Criação do token do usuário
        $user->token = $user->createToken($user->email)->accessToken;
        $user->imagem = asset($user->imagem);
        return $user;
    }

    public function perfil(Request $request)
    {
        $user = $request->user();
        $data = $request->all();

        if (isset($data['password'])) {
            $validacao = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'required|string|min:6|confirmed'
            ]);

            if ($validacao->fails()) {
                return $validacao->errors();
            }
            $user->password = bcrypt($data['password']);
        } else {
            $validacao = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ]);
            if ($validacao->fails()) {
                return $validacao->errors();
            }
            $user->name = $data['name'];
            $user->email = $data['email'];
        }

        if (isset($data['imagem'])) {


            $valiacao = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
               
            ]);
            if ($valiacao->fails()) {
                return $valiacao->errors();
            }
            $user->password = bcrypt($data['password']);
        } else {
            $valiacao = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            ]);

            if ($valiacao->fails()) {
                return $valiacao->errors();
            }
            $user->name = $data['name'];
            $user->email = $data['email'];
        }

        if (isset($data['imagem'])) {


            Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
                $explode = explode(',', $value);
                $allow = ['png', 'jpg', 'svg', 'jpeg'];
                $format = str_replace(
                    [
                        'data:image/',
                        ';',
                        'base64',
                    ],
                    [
                        '', '', '',
                    ],
                    $explode[0]
                );
                // check file format
                if (!in_array($format, $allow)) {
                    return false;
                }
                // check base64 format
                if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                    return false;
                }
                return true;
            });

            $valiacao = Validator::make($data, [
                'imagem' => 'base64image',

            ], ['base64image' => 'Imagem inválida']);

            if ($valiacao->fails()) {
                return $valiacao->errors();
            }

            //Pegar o tempo segundos etc...
            $time = time();
            //Diretorio perfis, será o diretório pai
            $diretorioPai = 'perfis';
            //Diretorio Imagem, sera a pasa de imagens dentro da pasta de perfis
            $diretorioImagem = $diretorioPai . DIRECTORY_SEPARATOR . 'perfil_' . $user->id;
            //Extensão da imagem
            $ext = substr($data['imagem'], 11, strpos($data['imagem'], ';') - 11);

            $url = $diretorioImagem . DIRECTORY_SEPARATOR . $time . '.' . $ext;

            $file = str_replace('data:image/' . $ext . ';base64,', '', $data['imagem']);
            $file = base64_decode($file);

            //Se não existir o diretorio pai, o mkdir vai criar
            if (!file_exists($diretorioPai)) {
                mkdir($diretorioPai, 0700);
            }
            //Se já houver uma imagem no diretorio, exclui primeiro para depois salvar a nova imagem
            if ($user->imagem) {
                if (file_exists($user->imagem)) {
                    unlink($user->imagem);
                }
            }

            //Se não existir o diretorio filho o mkdir irá criar
            if (!file_exists($diretorioImagem)) {
                mkdir($diretorioImagem, 0700);
            }

            file_put_contents($url, $file);
            $user->imagem = $url;
        }

        $user->save();
        $user->imagem = asset($user->imagem);
        $user->token = $user->createToken($user->email)->accessToken;
        return $user;
    }
    public function usuario(Request $request){
        return $request->user();
    }
}
