<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static paginate()
 * @method find($id)
 * @method create(array $distribuidorData)
 * @method findOrFail($id)
 */
class Distribuidor extends Model
{
    protected $fillable = [
		'razao_social', 'nome_fantasia', 'cnpj', 'user_id'
    ];

    public function produtos()
    {
    	return $this->belongsToMany(Produto::class, 'produto_distribuidor')
            ->as('extra')
            ->withPivot('preco', 'em_estoque');
    }

    public function produtosEmEstoque()
    {
        return $this->belongsToMany(Produto::class, 'produto_distribuidor')
            ->as('extra')
            ->wherePivot('em_estoque', true)
            ->withPivot('preco', 'em_estoque');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
