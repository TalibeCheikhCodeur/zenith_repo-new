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
//  $user = User::findOrFail($id);

//  // Mettre à jour les informations de l'utilisateur
//  $user->update($request->only(['nom', 'prenom', 'nom_client', 'adresse', 'code_client', 'role', 'email', 'telephone']));

//  // Mettre à jour le mot de passe si fourni
//  if ($request->filled('password')) {
//      $user->update(['password' => bcrypt($request->password)]);
//  }

//  // Vérifier les modulesClient pour ajout ou mise à jour
//  if ($request->has('modulesClient')) {
//      $modulesData = [];

//      foreach ($request->modulesClient as $module) {
//          $modulesData[$module['module_id']] = [
//              'numero_serie' => $module['numero_serie'],
//              'version' => $module['version'],
//              'code_annuel' => $module['code_annuel'],
//              'code_activation' => $module['code_activation'],
//              'nbre_users' => $module['nbre_users'],
//              'nbre_salariés' => $module['nbre_salariés'],
//              'date_fin_validite' => $module['date_fin_validite'],
//          ];

//          $oldModule = $user->modules()->where('module_id', $module['module_id'])->first();

//          if ($oldModule && $oldModule->pivot->code_annuel != $module['code_annuel'])
//          {

//              // dd("OK");
//            $user->modules()->updateExistingPivot($oldModule->id, ['etat' => 0]);

//            $user->modules()->attach($module['module_id'], [
//              'numero_serie' => $module['numero_serie'],
//              'version' => $module['version'],
//              'code_annuel' => $module['code_annuel'],
//              'code_activation' => $module['code_activation'],
//              'nbre_users' => $module['nbre_users'],
//              'nbre_salariés' => $module['nbre_salariés'],
//              'date_fin_validite' => $module['date_fin_validite'],
//              'etat' => 0
//             ]);
//          }     
//      }

      
     

//      // dd($modulesData);
//      // Synchroniser uniquement les modules avec etat = 1
//      $user->modules()->sync($modulesData);

//      // dd($user->modules()->get());
     
//      // Désactiver les modules qui ne sont plus dans le tableau et qui ont un etat de 1
//      $currentModuleIds = $user->modules()->where('etat', 1)->pluck('module_id')->toArray();
//      $newModuleIds = array_keys($modulesData);

//      // Trouver les modules à désactiver
//      $modulesToDeactivate = array_diff($currentModuleIds, $newModuleIds);
//      // dd($modulesToDeactivate);

//      foreach ($modulesToDeactivate as $moduleId)
//      {
//          $user->modules()->updateExistingPivot($moduleId, ['etat' => 0]);
//      }
//  }

//  return $this->response(Response::HTTP_OK, "Utilisateur mis à jour avec succès.", ["utilisateur" => $user]);
// }

