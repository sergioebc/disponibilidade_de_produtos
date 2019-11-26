<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistribuidorCollection;
use App\Http\Resources\DistribuidorResource;
use App\Models\Distribuidor;
use App\Utils\ApiError;
use Illuminate\Http\Request;

class DistribuidorController extends Controller
{

    /**
     * @var Distribuidor
     */
    private $distribuidor;

    public function __construct(Distribuidor $distribuidor)
    {
        $this->distribuidor = $distribuidor;
    }

    public function index()
    {
        return new DistribuidorCollection($this->distribuidor::paginate());
    }

    public function show($id)
    {
        $distribuidor = $this->distribuidor->find($id);

        if (!$distribuidor) return response()->json(ApiError::errorMessage('Distribuidor não encontrado!', 4040), 404);

        return new DistribuidorResource($distribuidor);
    }

    public function store(Request $request)
    {
        try {

            $distribuidorData = $request->all();
            $distribuidor = $this->distribuidor->create($distribuidorData);

            $data = ['data' => $distribuidor];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar salvar o distribuidor', 5000),  500);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $distribuidorData = $request->all();
            $distribuidor     = $this->distribuidor->find($id);

            if (!$distribuidor) return response()->json(ApiError::errorMessage('Distribuidor não encontrado!', 4040), 404);

            $distribuidor->update($distribuidorData);

            $data = ['data' => $distribuidor];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar atualizar o distribuidor', 5000), 500);
        }
    }

    public function delete($id)
    {
        try {

            $distribuidor = $this->distribuidor->find($id);

            if (!$distribuidor) return response()->json(ApiError::errorMessage('Distribuidor não encontrado!', 4040), 404);

            $distribuidor->delete();

            return response()->json(['data' => ['msg' => 'Distribuidor: ' . $distribuidor->nome_fantasia . ' removido com sucesso!']], 200);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar remover', 5000),  500);
        }
    }


    public function store_produtos(Request $request, $id)
    {
        $data = $request->all();
        $dataAsync = array();
        try {
            $distribuidor = $this->distribuidor->findOrFail($id);

            foreach ($data as $key => &$value) {
                $dataAsync[$value['produto_id']] = ['em_estoque' => $value['em_estoque'], 'preco' => $value['preco']];
            }

            if (count($data)) {
                $distribuidor->produtos()->syncWithoutDetaching($dataAsync);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Produtos cadastrados com sucesso!'
                ]
            ]);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar salvar os produtos do distribuidor', 5000),  500);
        }
    }

    public function show_produtos($id)
    {
        try {
            $distribuidor = $this->distribuidor->findOrFail($id);

            return response()->json($distribuidor->produtos()->paginate());

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar recuperar os produtos do distribuidor', 5000),  500);
        }
    }

}
