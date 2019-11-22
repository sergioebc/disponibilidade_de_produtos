<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdutoCollection;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use App\Utils\ApiError;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    /**
     * @var Produto
     */
    private $produto;

    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

    public function index(Request $request)
    {
        $produto = $this->produto;
        if ($request->has('fields')) {
            $fields = $request->get('fields');
            $produto = $produto->selectRaw($fields);
        }
        return new ProdutoCollection($produto->paginate(10));
    }

    public function show($id)
    {
        $produto = $this->produto->find($id);

        if (!$produto) return response()->json(ApiError::errorMessage('Produto não encontrado!', 4040), 404);

        return new ProdutoResource($produto);
    }

    public function store(Request $request)
    {
        try {

            $produtoData = $request->all();
            $produto = $this->produto->create($produtoData);

            $data = ['data' => $produto];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar salvar o produto', 1010),  500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $produtoData = $request->all();
            $produto     = $this->produto->find($id);
            $produto->update($produtoData);

            $data = ['data' => $produto];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar atualizar o produto', 1011), 500);
        }
    }

    public function delete($id)
    {
        try {

            $produto = $this->produto->find($id);

            if (!$produto) return response()->json(ApiError::errorMessage('Produto não encontrado!', 4040), 404);

            $produto->delete();

            return response()->json(['data' => ['msg' => 'Produto: ' . $produto->nome . ' removido com sucesso!']], 200);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar remover', 1012),  500);
        }
    }
}
