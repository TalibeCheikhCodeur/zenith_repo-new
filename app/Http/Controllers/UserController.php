<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Jobs\SendEmailJob;
use App\Models\User;
use App\Traits\FormatResponse;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\ClientResource;

class UserController extends Controller
{
    use FormatResponse;

    const MESSAGE_USER = 'Utilisateur créé avec succes';
    const MESSAGE_PASSWORD = 'Voici votre mot de passe par defaut: ';
    /**
     * Display a listing of the resource.
     */
    public function allClients()
    {
        $clients = ClientResource::collection(User::where('role', 'client')->get());
        return $this->response(Response::HTTP_OK, 'Voici la liste des clients', ['clients' => $clients]);
    }

    public function allUsers()
    {
        $users = UserResource::collection(User::whereNot('role', 'client')->get());
        return $this->response(Response::HTTP_OK, 'Voici la liste des utilisateurs', ['users' => $users]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $allRequest = $request->all();
        $newUser = [
            "nom" => $allRequest['nom'] ?? null,
            "nom_client" => $allRequest['nom_client'] ?? null,
            "code_client" => $allRequest['code_client'] ?? null,
            "prenom" => $allRequest['prenom'] ?? null,
            "role" => $allRequest['role'],
            "email" => $allRequest['email'],
            "password" => $allRequest['password'],
            "telephone" => $allRequest['telephone'],
        ];

        DB::transaction();

        try {

            $user = User::create($newUser);

            $details = [
                "title" => "Informations de connexion",
                "body" => UserController::MESSAGE_PASSWORD . 12345678 . ". Vous pouvez le changer en vous connectant via ce lien: http://localhost:4200/"
            ];

            DB::commit();
            SendEmailJob::dispatch($details, [$newUser['email']]);

            if ($request->code_client != null) {
                return $this->response(Response::HTTP_OK, UserController::MESSAGE_USER, ["utilisateur" => new ClientResource($user)]);
            }
            return $this->response(Response::HTTP_OK, UserController::MESSAGE_USER, ["utilisateur" => new UserResource($user)]);
        } catch (\Throwable $th) {
            
            DB::rollBack();

            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, $th->getMessage(), []);
        }
    }


    public function insertData(ExportRequest $request)
    {
        $allRequest = $request->all();
        $newUsers = [];
        foreach ($allRequest as $req) {
            $newUsers[] = [
                "nom" => $req['nom'] ?? null,
                "nom_client" => $req['nom_client'] ?? null,
                "code_client" => $req['code_client'] ?? null,
                "prenom" => $req['prenom'] ?? null,
                "role" => $req['role'],
                "email" => $req['email'],
                "password" => bcrypt($req['password']),
                "telephone" => $req['telephone'],
            ];
        }

        User::insert($newUsers);

        foreach ($newUsers as $user) {
            $details = [
                "title" => "Informations de connexion",
                "body" => UserController::MESSAGE_PASSWORD . 12345678 . ". Vous pouvez le changer en vous connectant via ce lien: http://localhost:4200/"
            ];
            SendEmailJob::dispatch($details, [$user['email']]);
        }

        return $this->response(Response::HTTP_OK, UserController::MESSAGE_USER, ["utilisateur" => $newUsers]);
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        if ($request->has("code_client")) {
            return $this->response(Response::HTTP_OK, "Modification réussie !", ["utilisateur" => new ClientResource($user)]);
        }
        return $this->response(Response::HTTP_OK, "Modification réussie !", ["utilisateur" => new UserResource($user)]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);
        $user->delete();
        $responseData = [
            'users' => new UserResource($user),
        ];
        return $this->response(Response::HTTP_OK, 'Utilisateur supprimé avec succès', $responseData);
    }
}
