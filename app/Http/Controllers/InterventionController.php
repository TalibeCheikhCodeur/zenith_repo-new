<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\NotifMail;
use App\Jobs\SendEmailJob;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\FormatResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Module_intervention;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\InterventionResource;
use App\Http\Requests\StoreInterventionRequest;
use App\Http\Requests\UpdateInterventionRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller

{
    use FormatResponse;
    /*
        le client fait une demande d'intervation
        cette methode insère des données dans la table intervation sur les champs description et module_id
    */
    public function index()
    {
        $interventions = InterventionResource::collection(Intervention::all());
        return $this->response(Response::HTTP_OK, "Voici la listes des interventions", ['interventions' => $interventions]);
    }

    public static function calculateDuration($startTime, $endTime)
    {
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);

        // Si l'heure de fin est avant l'heure de début, on assume que c'est le jour suivant
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $differenceInMinutes = $end->diffInMinutes($start);
        $hours = floor($differenceInMinutes / 60);
        $minutes = $differenceInMinutes % 60;

        return sprintf('durée = %02dH%02dMin', $hours, $minutes);
    }


    public function askIntervention(Request $request)
    {
        // Validation des entrées (optionnel mais recommandé)
        $request->validate([
            'module_ids'   => 'required|array',
            'module_ids.*' => 'exists:modules,id', // Assurez-vous que le module existe dans la table modules
        ]);

        $description = $request->input('description');

        $moduleIds = $request->input('module_ids');

        DB::beginTransaction();
        try {

            $intervention = Intervention::find($request->idInt);

            if ($intervention == null) 
            {
                $intervention = new Intervention();
            }

            $intervention->description = $description;

            if ($request->file('image') !== null)
            {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $imageName = time().'.'.$ext;
                $file->move(public_path()."/uploads/images/",$imageName);
                $intervention->image = $imageName;
                $intervention->path_image = asset('uploads/images/' . $imageName);
            } else
            {
                if (!$intervention->exists)
                {
                    $intervention->image = null;
                    $intervention->path_image = null;
                }
            }
            
            $intervention->user_id = 1;

            $intervention->save();

            if (!empty($moduleIds))
            {
                Module_intervention::where('intervention_id', $intervention->id)->delete();
                
                foreach ($moduleIds as $moduleId)
                {
                    Module_intervention::create([
                        'module_id' => $moduleId,
                        'intervention_id' => $intervention->id,
                    ]);
                }
            }

            // Validation de la transaction
            DB::commit();
            $mails = User::whereIn('role', ['COT', 'DPT'])->pluck('email');
            $users = User::whereIn('role', ['COT', 'DPT'])->select('prenom')->get();

            $recipients = [
                'title' => 'Zenith_international',
                'body' => 'un client a fait une nouvelles demande',
                'user' => $users
            ];
            dispatch(new SendEmailJob($recipients, $mails)); 
            return $this->response(Response::HTTP_OK, "La demande a été envoyée avec succès", ["intervention" => new InterventionResource($intervention)]);
           
        } catch (\Exception $e) {
            // Annulation de la transaction en cas d'erreur
            DB::rollBack();

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Une erreur s\'est produite lors de l\'envoi de la demande',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
      

    }

    /*
        la chargé des opération ou le DG peut assigner une intervation à un consultant
        cette methode récupère l'id d'une ion et insère dans le champ userId
        et change la valeur de isAssigned=true
    */
    public function asignIntervention($interventionId, $userId)
    {
        $intervention = Intervention::findOrFail($interventionId);
        $user = User::where('id', $userId)->first();
        if (!$user) {
            return $this->response(Response::HTTP_OK, "L\'utilisateur n'existe pas", []);
        }
        $this->sendMail([$user->email], $intervention->description, $intervention->caractere_intervention);
        $intervention->user_id = $userId;
        $intervention->isAssigned = true;

        $intervention->save();
        return $this->response(Response::HTTP_OK, "L\'intervention a bien été affectée au consultant", ["intervention" => new InterventionResource($intervention)]);
    }

    public function sendMail($mail, $description = null, $caractere_intervention = null)
    {
        $recipients = [
            'title' => 'Zenith_international',
            'body' => 'Une intervention vous a été assignée',
            
        ];
        dispatch(new SendEmailJob($recipients, $mail));
        
    }

    /*
        le consultant renseigne  ses informations pour la realisation de l'ion
        cette methode récupère l'id d'une ion et insère dans les autres champs
    */
    public function ficheIntervention(Request $request, $interventionId)
    {
        $intervention = Intervention::findOrFail($interventionId);

        $dateDebut = $request->input('debut_intervention');
        $dateFin = $request->input('fin_intervention');
        $date = $request->input('date_intervention');
        $typeIntervention = $request->input('types_intervention');
        $caractereInter = $request->input('caractere_intervention');
        $trableShooting = $request->input('trableShooting');

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $dateDebut);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $dateFin);
        $differenceInMinutes = $end->diffInMinutes($start);
        $hours = floor($differenceInMinutes / 60);
        $minutes = $differenceInMinutes % 60;
        $duree = sprintf('%02dh%02dMin', $hours, $minutes);

        $intervention->debut_intervention = $dateDebut;
        $intervention->fin_intervention = $dateFin;
        $intervention->date_intervention = $date;
        $intervention->types_intervention = $typeIntervention;
        $intervention->caractere_intervention = $caractereInter;
        $intervention->durée = $duree;
        $intervention->trableShooting = $trableShooting;

        $intervention->save();

        return $this->response(Response::HTTP_OK, 'Fiche enregistrée avec succès', [
            'intervention' => new InterventionResource($intervention),
            'duree' => $duree
        ]);
    }

    /*
        la charger des opération et le DG doit voir tous les demandes d'ions
        cette methode fait une select sur la table ion where isAssigned=false
    */
    public function allAskInterventions()
    {
        $askInterventions = Intervention::where('isAssigned', false)
            ->with(['modules', 'user'])
            ->get();
        return $this->response(Response::HTTP_OK, "tous les demandes d'intervations", ["intervation" => InterventionResource::collection($askInterventions)]);
    }


    public function allFiches()
    {
        $fiches = Intervention::with(['modules', 'user'])
            ->whereNotNull(['user_id', 'debut_intervention'])
            ->get();

        return $this->response(
            Response::HTTP_OK,
            "tous les fiches",
            ["interventions" => InterventionResource::collection($fiches)]
        );
    }

    public function clotured($interventionId)
    {
        $intervention = Intervention::findOrFail($interventionId);
        $intervention->isClotured = true;
        $intervention->save();

        return $this->response(
            Response::HTTP_OK,
            "Cloturé avec succès",
            ["intervention" => new InterventionResource($intervention)]
        );
    }




    public function destroy(Intervention $intervention)
    {
       $intervention->delete();
       $interventions = Intervention::all();
       return $this->response(Response::HTTP_OK, "Intervention supprimé avec succès",["interventions"=>InterventionResource::collection($interventions)]);
    }
}
