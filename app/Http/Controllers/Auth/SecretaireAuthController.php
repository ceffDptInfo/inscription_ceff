<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Secretaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\ResetPasswordMail;

class SecretaireAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('secretaire')->attempt(['email' => $request->email, 'password' => $request->password])) {

            $request->session()->regenerate();

            return redirect('liste-candidats');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('secretaire')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/secretaire-login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:secretaires',
            'password' => 'required|string',
        ]);

        $secretaire = new Secretaire();

        $secretaire->email = $request->email;
        $secretaire->password = bcrypt($request->password);

        $secretaire->save();

        Auth::guard('secretaire')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('secretaire-login')->with('success', 'Secretaire créé avec succès');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:secretaires,email'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetLink = route('secretaire.password.reset', ['token' => $token, 'email' => $request->email]);

        Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

        return back()->with('status', 'lien envoyé');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:secretaires,email',
            'password' => 'required|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'token invalide']);
        }

        $secretaire = Secretaire::where('email', $request->email)->first();

        $secretaire->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('secretaire-login')->with('status', 'mot de passe réinitialisé');
    }
}
