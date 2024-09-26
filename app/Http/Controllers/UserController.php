<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\SendEmailJob;
use App\Models\ModuleClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\FormatResponse;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ExportRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\ClientResource;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    use FormatResponse;

    const MESSAGE_USER = 'Utilisateur créé avec succes';
    const MESSAGE_PASSWORD = 'Voici votre mot de passe par defaut: ';
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        return User::all();
    }

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
            "adresse" => $allRequest['adresse'] ?? null,
            "prenom" => $allRequest['prenom'] ?? null,
            "role" => $allRequest['role'],
            "email" => $allRequest['email'],
            "password" => $allRequest['password'],
            "telephone" => $allRequest['telephone'],
        ];

        $modulesClient = $request['modulesClient'];

        DB::beginTransaction();

        try {

            $user = User::create($newUser);

            $user->modules()->attach($modulesClient);

            $details = [
                "title" => "Informations de connexion",
                "body" => UserController::MESSAGE_PASSWORD . 12345678 . ". Vous pouvez le changer en vous connectant via ce lien: http://192.168.1.19:4200"
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
                "adresse" => $allRequest['adresse'] ?? null,
                "prenom" => $req['prenom'] ?? null,
                "role" => $req['role'],
                "email" => $req['email'],
                "password" => bcrypt($req['password']),
                "telephone" => $req['telephone'],
            ];
        }

        User::insert($newUsers);

        foreach ($allRequest as $req) {
            $createdUser = User::where('email', $req['email'])->first();

            $details = [
                "title" => "Informations de connexion",
                "body" => UserController::MESSAGE_PASSWORD . 12345678 . ". Vous pouvez le changer en vous connectant via ce lien: http://localhost:4200/"
            ];
            SendEmailJob::dispatch($details, [$req['email']]);

            $modulesData = [];
            foreach ($req['modulesClient'] as $module) {
                $modulesData[$module['module_id']] = [
                    'numero_serie' => $module['numero_serie'],
                    'version' => $module['version'],
                    'code_annuel' => $module['code_annuel'],
                    'code_activation' => $module['code_activation'],
                    'nbre_users' => $module['nbre_users'],
                    'nbre_salariés' => $module['nbre_salariés'],
                    'etat' => $module['etat'],
                    'date_fin_validite' => $module['date_fin_validite ']
                ];
            }

            $createdUser->modules()->attach($modulesData);
        }

        return $this->response(Response::HTTP_OK, UserController::MESSAGE_USER, ["utilisateur" => $newUsers]);
    }




    public function updateData(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['nom', 'prenom', 'nom_client', 'adresse', 'code_client', 'role', 'email', 'telephone']));

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        if ($request->has('modulesClient')) {
            foreach ($request->modulesClient as $module) {
                $existingModule = $user->modules()
                    ->where('module_id', $module['module_id'])
                    ->where('code_annuel', $module['code_annuel'])
                    ->first();

                if ($existingModule) {
                    $user->modules()->updateExistingPivot($module['module_id'], [
                        'numero_serie' => $module['numero_serie'],
                        'version' => $module['version'],
                        'code_activation' => $module['code_activation'],
                        'nbre_users' => $module['nbre_users'],
                        'nbre_salariés' => $module['nbre_salariés'],
                    ]);
                } else {
                    $oldModule = $user->modules()
                        ->where('module_id', $module['module_id'])
                        ->first();

                    if ($oldModule && $oldModule->pivot->code_annuel != $module['code_annuel']) {
                        $user->modules()->updateExistingPivot($oldModule->id, ['etat' => 0]);
                    }

                    $user->modules()->attach($module['module_id'], [
                        'numero_serie' => $module['numero_serie'],
                        'version' => $module['version'],
                        'code_annuel' => $module['code_annuel'],
                        'code_activation' => $module['code_activation'],
                        'nbre_users' => $module['nbre_users'],
                        'nbre_salariés' => $module['nbre_salariés'],
                        'etat' => 1
                    ]);
                }
            }
        }

        return $this->response(Response::HTTP_OK, "Utilisateur mis à jour avec succès.", ["utilisateur" => $user]);
    }





    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->response(
            Response::HTTP_OK,
            "User Showing successfully",
            ["user" => new UserResource($user)]
        );
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
        try {
            $user->update($request->all());
            if ($request->has("code_client") && $request->code_client != null) {
                return $this->response(Response::HTTP_OK, "Modification réussie !", ["utilisateur" => new ClientResource($user)]);
            }
            return $this->response(Response::HTTP_OK, "Modification réussie !", ["utilisateur" => new UserResource($user)]);
        } catch (\Throwable $th) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "La modificaion a échouée !", []);
        }
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
