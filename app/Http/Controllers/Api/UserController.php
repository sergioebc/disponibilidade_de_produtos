<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Utils\ApiError;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $users = $this->user->paginate('10');

	    return response()->json($users, 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userData = $request->all();

        if (!$request->has('password') || !$request->get('password')) {
            return response()->json(ApiError::errorMessage('Senha obrigatória', 4010),  401);
        }

        try {
            $userData['password'] = bcrypt($userData['password']);
            $user = $this->user->create($userData);

            $data = ['data' => $user];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar salvar o user', 5000),  500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);

        if (!$user) return response()->json(ApiError::errorMessage('User não encontrado!', 4040), 404);

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userData = $request->all();

        if ($request->has('password') || $request->get('password')) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            unset($userData['password']);
        }

        try {
            $user     = $this->user->findOrFail($id);
            $user->update($userData);

            $data = ['data' => $user];
            return response()->json($data, 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar atualizar o user', 5000), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = $this->user->find($id);

            if (!$user) return response()->json(ApiError::errorMessage('User não encontrado!', 4040), 404);

            $user->delete();

            return response()->json(['data' => ['msg' => 'User: ' . $user->nome . ' removido com sucesso!']], 200);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao tentar remover o user', 5000),  500);
        }
    }
}
