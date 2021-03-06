<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conteudo_id',
        'texto',
        'data',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function conteudo(){
        return $this->belongsTo('App\Conteudo');
    }
}
