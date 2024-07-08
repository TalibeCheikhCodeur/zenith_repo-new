<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use App\Http\Resources\ClientResource;

class UserController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function allClients()
    {
        // $clients = User::where('role', 'client')->get();
        // return Response()->json(ClientResource::collection($clients));
        // By me
        $clients = ClientResource::collection( User::where('role', 'client')->get());
        return $this->response(Response::HTTP_OK, 'Voici la liste des clients', ['clients' => $clients]);
    }

    public function allUsers()
    {
        // $users = User::whereNot('role', 'client')->get();
        // return Response()->json(UserResource::collection($users));
        // By me
        $users = UserResource::collection( User::whereNot('role', 'client')->get());
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
    public function store(Request $request)
    {
        $minValidate='nullable|string|max:100';

        $request->validate([
            'nom' => $minValidate,
            'nom_client' => $minValidate,
            'code_client' => $minValidate,
            'prenom' => $minValidate,
            "telephone"=>$minValidate,
            'role' => 'required|in:consultant,DG,COT,DPT,client',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // le champ confirmed vérifie que password et password_confirmation sont identiques
        ]);

        $user = new User();
        $user->nom = $request->input('nom');
        $user->nom_client = $request->input('nom_client');
        $user->code_client = $request->input('code_client');
        $user->prenom = $request->input('prenom');
        $user->role = $request->input('role');
        $user->email = $request->input('email');
        $user->telephone = $request->input('telephone');
        $user->password = $request->input('password');
        $user->save();

        return response()->json(['message' => 'Utilisateur créé avec succès'], 200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
