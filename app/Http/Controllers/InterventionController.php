<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInterventionRequest;
use App\Http\Requests\UpdateInterventionRequest;
use App\Http\Resources\InterventionResource;
use App\Models\Intervention;
use App\Traits\FormatResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
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
        $moduleId = $request->input('module_id');
        $intervention = new Intervention();
        $intervention->description = $description;
        $intervention->module_id = $moduleId;

        $intervention->save();
        
        return $this->response(Response::HTTP_OK,"La demande a été envoyée avec succès",["intervation"=>$intervention]);
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
        return response()
            ->json([
                'message' => 'L\'intervention a bien été affectée au consultant',
                'data' => $intervention
            ], Response::HTTP_OK);
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
        return Response()->json(
            ['message' => 'Ajout reussi', 'data' => $intervention],
            Response::HTTP_OK
        );
    }

    /*
        la charger des opération et le DG doit voir tous les demandes d'ions
        cette methode fait une select sur la table ion where isAssigned=false
    */
    public function allAskInterventions()
    {
        $askInterventions = Intervention::where('isAssigned', false)->get();
        return $askInterventions;
    }


    public function allFiches()
    {
        $fiche = Intervention::whereNotNull(['user_id', 'debut_intervention'])->get();
        return Response()->json(InterventionResource::collection($fiche));
    }
}
