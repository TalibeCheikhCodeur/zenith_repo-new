<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Intervention;
use App\Http\Requests\StoreInterventionRequest;
use App\Http\Requests\UpdateInterventionRequest;
use App\Http\Resources\InterventionResource;
use App\Models\Module_intervention;
use App\Traits\FormatResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class InterventionController extends Controller

{
    use FormatResponse;
    /*
        le client fait une demande d'intervation
        cette methode insère des données dans la table intervation sur les champs description et module_id
    */
    public function askIntervention(Request $request)
    {
        $description = $request->input('description');
        $moduleIds = $request->input('module_ids');
    
        // Validation des entrées (optionnel mais recommandé)
        $request->validate([
            'description' => 'required|string',
            'module_ids' => 'required|array',
            'module_ids.*' => 'exists:modules,id', // Assurez-vous que le module existe dans la table modules
        ]);
    
        // Initialisation d'une transaction
        DB::beginTransaction();
    
        try {
            // Création et sauvegarde de l'intervention
            $intervention = new Intervention();
            $intervention->description = $description;
            $intervention->save();
    
            // Création et sauvegarde des liaisons module_interventions
            foreach ($moduleIds as $moduleId) {
                Module_intervention::create([
                    'module_id' => $moduleId,
                    'intervention_id' => $intervention->id,
                ]);
            }
    
            // Validation de la transaction
            DB::commit();
            return $this->response(Response::HTTP_OK,"La demande a été envoyée avec succès",["intervation"=>$intervention]);

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

        $intervention->user_id = $userId;
        $intervention->isAssigned = true;

        $intervention->save();
        
        return $this->response(Response::HTTP_OK,"L\'intervention a bien été affectée au consultant",["intervation"=>$intervention]);

    }

    /*
        le consultant renseigne  ses informations pour la realisation de l'ion
        cette methode récupère l'id d'une ion et insère dans les autres champs
    */
    public function ficheIntervention(Request  $request, $interventionId)
    {
        $intervention = Intervention::findOrFail($interventionId);

        $dateDebut = $request->input("debut_intervention");
        $dateFin = $request->input("fin_intervention");
        $date = $request->input("date_intervention");
        $typeIntervention = $request->input("types_intervention");
        $caractereInter = $request->input("caractere_intervention");


        $intervention->debut_intervention = $dateDebut;
        $intervention->fin_intervention = $dateFin;
        $intervention->date_intervention = $date;
        $intervention->types_intervention = $typeIntervention;
        $intervention->caractere_intervention = $caractereInter;

        $intervention->save();
        return $this->response(Response::HTTP_OK,"Fiche enregistrer avec succès",["intervation"=>InterventionResource::collection($intervention)]);

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
        return $this->response(Response::HTTP_OK,"tous les demandes d'intervations",["intervation"=>InterventionResource::collection($askInterventions)]);
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
}
