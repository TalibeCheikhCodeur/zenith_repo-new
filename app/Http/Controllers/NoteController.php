<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Traits\FormatResponse;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use Illuminate\Http\Response;



class NoteController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::all();
        return $this->response(Response::HTTP_OK, "Liste des notes récupérée avec succès", ["notes" => $notes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        // dd('success');

        $note = Note::create([

            'title' => $request->title,
            'points' => $request->points,
            'commentaire' => $request->commentaire,
            'intervention_id' => $request->intervention_id,

        ]);
        return $this->response(Response::HTTP_OK, "La note a été ajoutée avec succès", ["note" =>new NoteResource($note)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {

        if (!$note) {
            return $this->response(Response::HTTP_NOT_FOUND, "La note n'existe pas", ['note' => []]);
        }

        return $this->response(Response::HTTP_OK, "Note récupérée avec succès", ["note" => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {

        if (!$note) {
            return $this->response(Response::HTTP_NOT_FOUND, "La note n'existe pas", ['note' => []]);
        }

        $note->update([
            'title' => $request->title,
            'points' => $request->points,
            'commentaire' => $request->commentaire,
            'intervention_id' => $request->intervention_id,
        ]);

        return $this->response(Response::HTTP_OK, "La note a été mise à jour avec succès", ["note" => $note]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {

        if (!$note) {
            return $this->response(Response::HTTP_NOT_FOUND, "La note n'existe pas", ['note' => []]);
        }

        $note->delete();

        return $this->response(Response::HTTP_OK, "La note a été supprimée avec succès", ['note' => $note]);
    }
}
