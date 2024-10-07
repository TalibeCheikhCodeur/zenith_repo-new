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
        if (!Auth::attempt($request->only("email", "password"))) {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Login ou mot de passe incorrect", []);
        }

        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;

        $expiresAt = Carbon::now()->addHours(24);  // Token expires in 24 hours

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
            'expires_at' => $expiresAt->toISOString()  // Send expiration in the response
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
