<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\FormatResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class forgotPasswordController extends Controller
{
    use FormatResponse;
    public function forgot(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);
        $email = $request->email;

        if (User::where('email', $email)->doesntExist()) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "cet email n'existe pas", []);

        }
        $token = Str::random(10);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()->addHours(1)
        ]);
        // send mail
        $recipients = [
            'title' => 'Demande de Changement de mot de passe',
            'body' => 'Pour réinitialiser votre mot de passe veuillez suivre ce lien : http://localhost:4200/auth/resetPassword?token=' . $token,
        ];
        dispatch(new SendEmailJob($recipients, [$email]));
        return $this->response(Response::HTTP_OK, "Vérifier votre courrier", ['token' => $token]);

    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $token = $request->token;
        $passwordRest = DB::table('password_reset_tokens')->where('token', $token)->first();

        // Verification
        if (!$passwordRest) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "token introuvable", ['token' => []]);
        }

        // token expiré
        if (!$passwordRest->created_at >= now()) {
            return response(['message' => 'Token has expired.'], 200);
        }

        $user = User::where('email', $passwordRest->email)->first();

        if (!$user) {
            return $this->response(Response::HTTP_INTERNAL_SERVER_ERROR, "l'utilisateur n'existe pas", ['user' => []]);

        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('token', $token)->delete();

        return $this->response(Response::HTTP_OK, "mot de passe mis à jour avec succès", []);

    }

}
