<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Imagem;
use App\Utils\ApiError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagemController extends Controller
{
    private $imagem;

    public function __construct(Imagem $imagem)
    {
        $this->imagem = $imagem;
    }

    public function setThumb($imagemId, $produtoId)
    {
        try {

            $img = $this->imagem
                ->where('produto_id', $produtoId)
                ->where('is_thum', true);

            if($img->count()) $img->first()->update(['is_thum' => false]);

            $img = $this->imagem->find($imagemId);
            $img->update(['is_thum' => true]);

            return response()->json([
                'data' => [
                    'msg' => 'Thumb atualizada com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar alterar a Thumb', 5000), 500);
        }
    }

    public function remove($imagemId)
    {
        try {

            $img = $this->imagem->find($imagemId);

            if($img->is_thum) {
                return response()->json(ApiError::errorMessage('Não é possivel remover foto de thumb, selecione outra thumb e remova a imagem desejada!', 5000),  500);
            }

            if($img) {
                Storage::disk('public')->delete($img->imagem);
                $img->delete();
            }

            return response()->json([
                'data' => [
                    'msg' => 'Foto removida com sucesso!'
                ]
            ], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar remover a imagem', 5000), 500);
        }
    }
}
