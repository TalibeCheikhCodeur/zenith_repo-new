<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultantMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consultant Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}



// public function updateData(Request $request, $id)
// {
//     $user = User::findOrFail($id);

//     $user->update($request->only(['nom', 'prenom', 'nom_client', 'adresse', 'code_client', 'role', 'email', 'telephone']));

//     if ($request->filled('password'))
//     {
//         $user->update(['password' => bcrypt($request->password)]);
//     }

//     if ($request->has('modulesClient')) 
//     {
//         $existingModules = $user->modules()->wherePivot('etat', 1)->pluck('module_id')->toArray();
//         $modulesInRequest = [];

//         foreach ($request->modulesClient as $module)
//         {
//             // Ajouter le module_id à la liste des modules de la requête
//             $modulesInRequest[] = $module['module_id'];

//             $existingModule = $user->modules()
//                 ->where('module_id', $module['module_id'])
//                 ->where('code_annuel', $module['code_annuel'])
//                 ->first();

//             if (isset($existingModule)) 
//             {
//                 // Mettre à jour le pivot si le module existe
//                 $user->modules()->updateExistingPivot($module['module_id'], [
//                     'numero_serie' => $module['numero_serie'],
//                     'version' => $module['version'],
//                     'code_activation' => $module['code_activation'],
//                     'nbre_users' => $module['nbre_users'],
//                     'nbre_salariés' => $module['nbre_salariés'],
//                     'date_fin_validite' => $module['date_fin_validite']
//                 ]);
//             } 
//             else 
//             {
//                 // Gérer les anciens modules avec un code_annuel différent
//                 $oldModule = $user->modules()
//                     ->where('module_id', $module['module_id'])
//                     ->first();

//                 if ($oldModule && $oldModule->pivot->code_annuel != $module['code_annuel']) 
//                 {
//                     // dd("Ok");
//                     // Désactiver l'ancien module dont l'état est égal à 1
//                     $user->modules()->updateExistingPivot($oldModule->id, ['etat' => 0]);
//                 }

//                 // Attacher le nouveau module
//                 $user->modules()->attach($module['module_id'], [
//                     'numero_serie' => $module['numero_serie'],
//                     'version' => $module['version'],
//                     'code_annuel' => $module['code_annuel'],
//                     'code_activation' => $module['code_activation'],
//                     'nbre_users' => $module['nbre_users'],
//                     'nbre_salariés' => $module['nbre_salariés'],
//                     'date_fin_validite' => $module['date_fin_validite'],
//                     'etat' => 1
//                 ]);
//             }
//         }

//         // Désactiver les modules dont l'état est égal à 1 et qui ne sont plus dans la requête
//         $modulesToDeactivate = array_diff($existingModules, $modulesInRequest);
//         // dd($modulesToDeactivate);
//         if (!empty($modulesToDeactivate)) 
//         {
//            $user->modules()->wherePivot('etat', 1)->whereIn('module_id', $modulesToDeactivate)->update(['etat' => 0]);
//         }
//     }

//     return $this->response(Response::HTTP_OK, "Utilisateur mis à jour avec succès.", ["utilisateur" => $user]);
// }