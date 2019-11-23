<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribuidor extends Model
{
    protected $fillable = [
		'razao_social', 'nome_fantasia', 'cnpj', 'user_id'
    ];
}
