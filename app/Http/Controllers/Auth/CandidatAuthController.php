<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CandidatLoginMail;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class CandidatAuthController extends Controller
{
    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $candidatExistant = Candidat::where('email', $request->email)->first();

        if ($candidatExistant && $candidatExistant->statut === 'Candidature complète') {
            return back()->withErrors(['email' => 'Vous avez déjà soumis votre candidature.']);
        }

        $token = Str::random(64);

        if ($candidatExistant) {
            $candidatExistant->token = $token;
            $candidatExistant->save();
            $candidat = $candidatExistant;
        } else {
            $nouveauCandidat = new Candidat();
            $nouveauCandidat->email = $request->email;
            $nouveauCandidat->token = $token;
            $nouveauCandidat->statut = 'Nouveau Candidat';
            $nouveauCandidat->date_creation = now();
            $nouveauCandidat->save();

            $candidat = $nouveauCandidat;
        }

        $urlVerification = url('/verify-candidat/' . $token);

        Mail::to($candidat->email)->send(new CandidatLoginMail($urlVerification));

        return back()->with('status', 'lien de connexion envoyé');
    }

    public function verifyToken($token)
    {
        $candidat = Candidat::where('token', $token)->first();

        if (!$candidat) {
            return redirect('/candidat-login')->withErrors(['email' => 'lien invalide']);
        }

        session(['candidat_id' => $candidat->id]);

        return redirect('/donnees-personnelles');
    }

    public function logout(Request $request)
    {
        session()->forget('candidat_id');

        return redirect('/candidat-login');
    }
}
