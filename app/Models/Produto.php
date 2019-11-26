<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{

    protected $fillable = [
		'nome', 'codigo_de_barras', 'descricao'
    ];

    public function distribuidors()
    {
    	return $this->belongsToMany(Distribuidor::class, 'produto_distribuidor');
    }

    public function imagens()
    {
        return $this->hasMany(Imagem::class);
    }
}
