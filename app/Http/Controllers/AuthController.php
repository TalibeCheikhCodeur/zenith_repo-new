<?php

namespace App\Http\Controllers;

use App\Traits\FormatResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use FormatResponse;
    public function login(Request $request)
    {

        $loginField = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'code_client';

        if (!Auth::attempt([$loginField => $request->input('email'), 'password' => $request->input('password')])) {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Login ou mot de passe incorrect", []);
        }

        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;

        $expiresAt = Carbon::now()->addMinutes(2);  // Le Token va expirer sous 24 h

        $user->tokens()->create([
            'name' => 'auth_token',
            'token' => hash('sha256', $token),
            'abilities' => ['*'],
            'expires_at' => $expiresAt,  // Save the expiration time
        ]);

        $cookie = cookie('token', $token, 24 * 60);

        return response([
            'id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'role' => $user->role,
            'nom_client' => $user->nom_client,
            'code_client' => $user->code_client,
            'token' => $token,
            'expires_at' => $expiresAt->toISOString()
        ])->withCookie($cookie);

    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }
    }
}
