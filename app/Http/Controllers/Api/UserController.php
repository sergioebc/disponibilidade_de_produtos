<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use App\Utils\ApiError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        auth('api')->user()->authorizeRoles(['admin']);

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
        auth('api')->user()->authorizeRoles(['admin']);

        $userData = $request->all();

        if (!$request->has('password') || !$request->get('password')) {
            return response()->json(ApiError::errorMessage('Senha obrigatória', 4010),  401);
        }

        Validator::make($userData, [
            'razao_social' => 'required',
            'nome_fantasia' => 'required',
            'cnpj' => 'required'
        ])->validate();

        try {
            $role_admin = Role::where('name', 'admin')->first();

            $userData['password'] = bcrypt($userData['password']);
            $user = $this->user->create($userData);
            $user->roles()->attach($role_admin);

            $user =  $user->distribuidor()->create(
                [
                    'razao_social' => $userData['razao_social'],
                    'nome_fantasia' => $userData['nome_fantasia'],
                    'cnpj' => $userData['cnpj']
                ]
            );

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
        $userLogin = auth('api')->user();
        $user = null;

        $userLogin->authorizeRoles(['admin', 'vendedor']);

        try {
            $user = $this->user->with( 'distribuidor')->findOrFail($id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('User não encontrado!', 4040), 404);
        }

        if ( ($userLogin->Roles[0]->name == 'vendedor') && !($userLogin->id == $id) ) {
            return response()->json(ApiError::errorMessage('Você não tem acesso a este usuário', 401), 401);
        }
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

        $userLogin = auth('api')->user();
        $user = null;

        $userLogin->authorizeRoles(['admin', 'vendedor']);

        if ( ($userLogin->Roles[0]->name == 'vendedor') && !($userLogin->id == $id) ) {
            return response()->json(ApiError::errorMessage('Você não tem acesso a este usuário', 401), 401);
        }

        try {
            $user = $this->user->findOrFail($id);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 5000),  500);
            }
            return response()->json(ApiError::errorMessage('User não encontrado!', 4040), 404);
        }

        if ($request->has('password') || $request->get('password')) {
            $userData['password'] = bcrypt($userData['password']);
        } else {
            unset($userData['password']);
        }

        try {
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

        auth('api')->user()->authorizeRoles(['admin']);

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
