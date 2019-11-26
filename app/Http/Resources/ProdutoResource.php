<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nome' => $this->nome,
            'codigo_de_barras' => $this->codigo_de_barras,
            'descricao' => $this->descricao,
            'imagens' => ImagemResource::collection($this->imagens),
        ];

        // return all attributes
        // return $this->resource->toArray();
    }

}
