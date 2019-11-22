<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return response()->json($this->distribuidor->paginate(10));
    }

    public function show($id)
    {
        $distribuidor = $this->distribuidor->find($id);

        if (!$distribuidor) return response()->json(ApiError::errorMessage('Distribuidor não encontrado!', 4040), 404);

        $data = ['data' => $distribuidor];
        return response()->json($data);
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
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar salvar o distribuidor', 1010),  500);
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
                return response()->json(ApiError::errorMessage($e->getMessage(), 1011),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar atualizar o distribuidor', 1011), 500);
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
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar remover', 1012),  500);
        }
    }
}
