<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdutoRequest;
use App\Http\Resources\ProdutoCollection;
use App\Http\Resources\ProdutoResource;
use App\Models\Produto;
use App\Repository\ProdutoRepository;
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
        $produtoRepository = new ProdutoRepository($produto);

        if ($request->has('coditions')) {
            $produtoRepository->selectCoditions($request->get('coditions'));
        }

        if ($request->has('fields')) {
            $produtoRepository->selectFilter($request->get('fields'));
        }

        return new ProdutoCollection($produtoRepository->getResult()->paginate(10));
    }

    public function show($id)
    {
        $produto = $this->produto->find($id);

        if (!$produto) return response()->json(ApiError::errorMessage('Produto não encontrado!', 4040), 404);

        return new ProdutoResource($produto);
    }

    public function store(ProdutoRequest $request)
    {
        try {
            $produtoData = $request->all();
            $produto = $this->produto->create($produtoData);

            $data = ['data' => $produto];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar salvar o produto', 5000),  500);
        }
    }

    public function update(ProdutoRequest $request, $id)
    {
        try {
            $produtoData = $request->all();
            $produto     = $this->produto->findOrFail($id);
            $produto->update($produtoData);

            $data = ['data' => $produto];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar atualizar o produto', 5000), 500);
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
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar remover', 5000),  500);
        }
    }
}
