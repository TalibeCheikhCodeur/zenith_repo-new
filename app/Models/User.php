<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ["id"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_clients')->withPivot(['id', 'numero_serie', 'version', 'code_annuel', 'code_activation', 'nbre_users', 'nbre_salariés', 'etat', 'resilie', 'date_fin_validite']);
    }
    public function moduleClient(): HasMany
    {
        return $this->hasMany(ModuleClient::class);
    }

    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
}


// $modulesData = [];
// $invalidModules = [];

// // Grouper les données par module_id
// $groupedModules = [];
// foreach ($req['modulesClient'] as $data) {
//     if (isset($data['module_id'])) {
//         // Début d'un nouveau module
//         $groupedModules[] = ['module_id' => $data['module_id']];
//     } else {
//         // Ajouter les données au dernier module
//         $groupedModules[count($groupedModules) - 1] = array_merge(
//             $groupedModules[count($groupedModules) - 1],
//             $data
//         );
//     }
// }

// // Traiter chaque module groupé
// foreach ($groupedModules as $module) {
//     if (isset($module['module_id'])) {
//         // Séparer la gamme et le produit
//         [$gammeNom, $nomProduit] = explode('_', $module['module_id'], 2);

//         // Trouver la gamme correspondante
//         $gamme = Gamme::where('libelle', $gammeNom)->first();

//         if ($gamme) {
//             // Trouver le module correspondant à la gamme et au produit
//             $moduleRecord = Module::where('gamme_id', $gamme->id)
//                                   ->where('nom_produit', $nomProduit)
//                                   ->first();

//             if ($moduleRecord)
//             {
//                 // Conversion de la date au format 'Y-m-d'
//                 $dateFinValidite = null;
//                 if (!empty($module['date_fin_validite']))
//                 {
//                     $dateFinValidite = \Carbon\Carbon::createFromFormat('d/m/Y', $module['date_fin_validite'])->format('Y-m-d');
//                 }
//                 // Ajouter les données pour ce module
//                 $modulesData[$moduleRecord->id] = [
//                     'numero_serie' => $module['numero_serie'] ?? null,
//                     'version' => $module['version'] ?? null,
//                     'code_annuel' => $module['code_annuel'] ?? null,
//                     'code_activation' => $module['code_activation'] ?? null,
//                     'nbre_users' => $module['nbre_users'] ?? null,
//                     'nbre_salariés' => $module['nbre_salariés'] ?? null,
//                     'etat' => 1,
//                     'date_fin_validite' => $dateFinValidite,
//                 ];
//             } else {
//                 $invalidModules[] = $module['module_id'];
//             }
//         } else {
//             $invalidModules[] = $module['module_id'];
//         }
//     }
// }

// // Associer les modules à l'utilisateur
// if (!empty($modulesData)) {
//     $createdUser->modules()->attach($modulesData);
// }


// // Associer les modules à l'utilisateur si tous les modules sont valides

// }

// // Si des modules sont invalides, retourner une erreur
// if (!empty($invalidModules))
// {
// return $this->response(Response::HTTP_BAD_REQUEST, 'Certains modules sont invalides', [
// 'modules_invalides' => $invalidModules
// ]);
// }

// return $this->response(Response::HTTP_OK, UserController::MESSAGE_USER, ["utilisateur" => $newUsers]);
// }
