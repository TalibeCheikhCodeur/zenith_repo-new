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
use App\Models\Module;

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
            "telephone" => $allRequest['telephone'] ?? null,
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


    // Partie1 Khaoussou
    // public function insertData(ExportRequest $request)
    // {
    //     $allRequest = $request->all();
    //     $errorModules = [];
    //     $validUsers = []; // Utilisateurs valides ajoutés

    //     foreach ($allRequest as $req)
    //     {
    //         $moduleNames = array_column($req['modulesClient'], 'module_id');
    //         $modules = Module::whereIn('nom_produit', $moduleNames)->get()->keyBy('nom_produit');

    //         $modulesData = [];
    //         $hasError = false;

    //         foreach ($req['modulesClient'] as $module) 
    //         {
    //             if (!isset($module['module_id'])) {
    //                 $errorModules[] = [
    //                     'message' => "Clé 'module_id' manquante.",
    //                     'module_problematique' => $module,
    //                     'utilisateur' => $req['email'] ?? 'Inconnu'
    //                 ];
    //                 $hasError = true;
    //                 continue;
    //             }

    //             if (isset($modules[$module['module_id']])) {
    //                 $moduleRecord = $modules[$module['module_id']];
    //                 $modulesData[$moduleRecord->id] = [
    //                     'numero_serie' => $module['numero_serie'],
    //                     'version' => $module['version'],
    //                     'code_annuel' => $module['code_annuel'],
    //                     'code_activation' => $module['code_activation'],
    //                     'nbre_users' => $module['nbre_users'],
    //                     'nbre_salariés' => $module['nbre_salarie'] ?? null,
    //                     'etat' => $module['etat'] ?? 1,
    //                     'resilie' => $module['resilie'] ?? 0,
    //                     'date_fin_validite' => $module['date_fin_validite'],
    //                 ];
    //             } else
    //             {
    //                 $errorModules[] = [
    //                     'message' => "Module avec le nom {$module['module_id']} introuvable.",
    //                     'module_problematique' => $module,
    //                     'utilisateur' => $req['email'] ?? 'Inconnu'
    //                 ];
    //                 $hasError = true;
    //                 continue;
    //             }
    //         }

    //         // Si un module est invalide, on ignore cet utilisateur
    //         if ($hasError) {
    //             continue;
    //         }

    //         try {
    //             // Création d'un utilisateur
    //             $createdUser = User::create([
    //                 "nom" => $req['nom'] ?? null,
    //                 "nom_client" => $req['nom_client'] ?? null,
    //                 "code_client" => $req['code_client'] ?? null,
    //                 "adresse" => $req['adresse'] ?? null,
    //                 "prenom" => $req['prenom'] ?? null,
    //                 "role" => $req['role'],
    //                 "email" => $req['email'],
    //                 "password" => bcrypt($req['password']),
    //                 "telephone" => $req['telephone'],
    //             ]);

    //             // Association des modules à l'utilisateur
    //             $createdUser->modules()->attach($modulesData);

    //             // Envoi d'un email avec les informations de connexion
    //             $details = [
    //                 "title" => "Informations de connexion",
    //                 "body" => UserController::MESSAGE_PASSWORD . $req['password'] . ". Vous pouvez le changer en vous connectant via ce lien: http://192.168.1.19:4200"
    //             ];
    //             SendEmailJob::dispatch($details, [$req['email']]);

    //             $validUsers[] = $createdUser;
    //         } catch (\Exception $e)
    //         {
    //             // Enregistrer l'erreur de création d'utilisateur (duplication ou autres erreurs)
    //             $errorModules[] = [
    //                 'message' => "Erreur lors de la création de l'utilisateur : " . $e->getMessage(),
    //                 'utilisateur' => $req['email'] ?? 'Inconnu',
    //                 'exception' => $e->getMessage()
    //             ];
    //             // On retourne quand même l'objet utilisateur même s'il y a une erreur
    //             $req['error'] = 'Duplication ou autre erreur';
    //             $validUsers[] = $req;  // Ajoute l'utilisateur avec l'erreur dans la liste des utilisateurs valides
    //         }
    //     }

    //     return $this->response(
    //         Response::HTTP_OK,
    //         UserController::MESSAGE_USER,
    //         [
    //             "utilisateurs_insérés" => $validUsers,
    //             "erreurs_modules" => $errorModules,
    //         ]
    //     );
    // }


      public function insertData(ExportRequest $request)
      {
                $allRequest = $request->all();
                $errorModules = [];
                $validUsers = []; // Utilisateurs valides ajoutés

                foreach ($allRequest as $req)
                {
                    $moduleNames = array_column($req['modulesClient'], 'module_id');
                    $modules = Module::whereIn('nom_produit', $moduleNames)->get()->keyBy('nom_produit');

                    $modulesData = [];
                    $hasError = false;

                    foreach ($req['modulesClient'] as $module) 
                    {
                        if (!isset($module['module_id'])) {
                            $errorModules[] = [
                                'message' => "Clé 'module_id' manquante.",
                                'module_problematique' => $module,
                                'utilisateur' => $req['email'] ?? 'Inconnu'
                            ];
                            $hasError = true;
                            continue;
                        }

                        if (isset($modules[$module['module_id']])) {
                            $moduleRecord = $modules[$module['module_id']];
                            $modulesData[$moduleRecord->id] = [
                                'numero_serie' => $module['numero_serie'],
                                'version' => $module['version'],
                                'code_annuel' => str_replace(' ','', $module['code_annuel']),
                                'code_activation' => str_replace(' ', '', $module['code_activation']),
                                'nbre_users' => $module['nbre_users'],
                                'nbre_salariés' => $module['nbre_salarie'] ?? null,
                                'etat' => $module['etat'] ?? 1,
                                'resilie' => $module['resilie'] ?? 0,
                                'date_fin_validite' => $module['date_fin_validite'],
                            ];
                        } else
                        {
                            $errorModules[] = [
                                'message' => "Module avec le nom {$module['module_id']} introuvable.",
                                'module_problematique' => $module,
                                'utilisateur' => $req['email'] ?? 'Inconnu'
                            ];
                            $hasError = true;
                            continue;
                        }
                    }

                    // Si un module est invalide, on ignore cet utilisateur
                    if ($hasError)
                    {
                        continue;
                    }

                    // Vérification si l'utilisateur existe déjà
                    $existingUser = User::where('email', $req['email'])->first();

                    try {
                        if (!$existingUser) {
                            // Création d'un nouvel utilisateur
                            $createdUser = User::create([
                                "nom" => $req['nom'] ?? null,
                                "nom_client" => $req['nom_client'] ?? null,
                                "code_client" => $req['code_client'] ?? null,
                                "adresse" => $req['adresse'] ?? null,
                                "prenom" => $req['prenom'] ?? null,
                                "role" => $req['role'],
                                "email" => $req['email'] ?? "ziac-it@ziac.sn",
                                "password" => bcrypt($req['password']),
                                "telephone" => $req['telephone'],
                            ]);
                        } else {
                            // Utilisateur déjà existant
                            $createdUser = $existingUser;
                        }

                        // Vérification des modules déjà associés
                        $existingModules = $createdUser->modules()->pluck('module_id')->toArray();
                        $newModulesData = array_diff_key($modulesData, array_flip($existingModules));

                        // Association des nouveaux modules à l'utilisateur
                        if (!empty($newModulesData)) {
                            $createdUser->modules()->attach($newModulesData);
                        }

                        // Envoi d'un email uniquement si l'utilisateur est nouvellement créé
                        if (!$existingUser) {
                            $details = [
                                "title" => "Informations de connexion",
                                "body" => UserController::MESSAGE_PASSWORD . $req['password'] . ". Vous pouvez le changer en vous connectant via ce lien: https://zenith-erp.alwaysdata.net/"
                            ];
                            SendEmailJob::dispatch($details, [$req['email']]);
                        }

                        $validUsers[] = $createdUser;
                    } catch (\Exception $e)
                    {
                        // Enregistrer l'erreur de création d'utilisateur (duplication ou autres erreurs)
                        $errorModules[] = [
                            'message' => "Erreur lors de la création de l'utilisateur : " . $e->getMessage(),
                            'utilisateur' => $req['email'] ?? 'Inconnu',
                            'exception' => $e->getMessage()
                        ];
                    }
                }

                return $this->response(
                    Response::HTTP_OK,
                    UserController::MESSAGE_USER,
                    [
                        "utilisateurs_insérés" => $validUsers,
                        "erreurs_modules" => $errorModules,
                    ]
                );
    }

               
   


    public function updateData(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only(['nom', 'prenom', 'nom_client', 'adresse', 'code_client', 'role', 'email', 'telephone','etat']));
    
        if ($request->filled('password'))
        {
            $user->update(['password' => bcrypt($request->password)]);
        }
    
        if ($request->has('modulesClient')) 
        {
            $existingModules = $user->modules()->wherePivot('etat', 1)->pluck('module_id')->toArray();
            $modulesInRequest = [];

            foreach ($request->modulesClient as $module)
            {
                // Ajouter le module_id à la liste des modules de la requête
                $modulesInRequest[] = $module['module_id'];

                $existingModule = $user->modules()
                    ->where('module_id', $module['module_id'])
                    ->where('code_annuel', $module['code_annuel'])
                    ->first();

                if (isset($existingModule))
                {
                    // Mettre à jour le pivot si le module existe
                    $user->modules()->updateExistingPivot($module['module_id'], [
                        'numero_serie' => $module['numero_serie'],
                        'version' => $module['version'],
                        'code_activation' => $module['code_activation'],
                        'nbre_users' => $module['nbre_users'],
                        'nbre_salariés' => $module['nbre_salariés'],
                        'date_fin_validite' => $module['date_fin_validite']
                    ]);
                } else {
                    // Gérer les anciens modules avec un code_annuel différent
                    $oldModule = $user->modules()
                        ->where('module_id', $module['module_id'])
                        ->first();
    
                    if ($oldModule && $oldModule->pivot->code_annuel != $module['code_annuel']) 
                    {
                        // dd("Ok");
                        // Désactiver l'ancien module dont l'état est égal à 1
                        $user->modules()->updateExistingPivot($oldModule->id, ['etat' => 0]);
                    }

                    // Attacher le nouveau module
                    $user->modules()->attach($module['module_id'], [
                        'numero_serie' => $module['numero_serie'],
                        'version' => $module['version'],
                        'code_annuel' => $module['code_annuel'],
                        'code_activation' => $module['code_activation'],
                        'nbre_users' => $module['nbre_users'],
                        'nbre_salariés' => $module['nbre_salariés'],
                        'date_fin_validite' => $module['date_fin_validite'],
                        'etat' => 1
                    ]);
                }
            }

            // Désactiver les modules dont l'état est égal à 1 et qui ne sont plus dans la requête
            $modulesToDeactivate = array_diff($existingModules, $modulesInRequest);
            // dd($modulesToDeactivate);
            if (!empty($modulesToDeactivate)) {
                $user->modules()->wherePivot('etat', 1)->whereIn('module_id', $modulesToDeactivate)->update(['etat' => 0]);
            }
        }

        return $this->response(Response::HTTP_OK, "Utilisateur mis à jour avec succès.", ["utilisateur" => $user]);
    }

    public function rescindUsers(User $user)
    {
        $modulesClient = [];
        if ($user) {
            $modulesClient = ModuleClient::getModuleClient($user->id)->get();
            $user->update(["etat" => 0]);
            foreach ($modulesClient as $module) {
                $module->update([
                    "etat" => 0,
                    "resilie" => 1
                ]);
            }
            return $this->response(Response::HTTP_OK, "Ce client a bien été résilié !", ["utilisateur" => $user]);
        }
        return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "Ce client n'existe pas !", []);
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
