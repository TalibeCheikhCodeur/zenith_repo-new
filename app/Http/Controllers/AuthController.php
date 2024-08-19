<?php

namespace App\Http\Controllers;

use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use FormatResponse;
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Login ou mot de passe incorrect", []);
        }

        $user = Auth::user();
        // dd($user);
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);

        return response([
            "id" => $user->id,
            "nom" => $user->nom,
            "prenom" => $user->prenom,
            "email" => $user->email,
            "telephone" => $user->telephone,
            "role" => $user->role,
            "nom_client" => $user->nom_client,
            "code_client" => $user->code_client,
            "token" => $token
        ])->withCookie($cookie);
    }

    public function logout()
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
        return Response([
            'success' => true,
            'message' => " Déconnexion réussi."
        ], 200);
    }
}
