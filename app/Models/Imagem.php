<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagem extends Model
{
    protected $table = 'imagem';

    protected $fillable =[
        'imagem', 'is_thum'
    ];

    public function produtos() {
        return $this->belongsTo(Produto::class);
    }
}
