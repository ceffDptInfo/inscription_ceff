<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmationInscriptionMail;
use App\Models\Candidat;
// use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Mail;

class CandidatController extends Controller
{
    public function index()
    {
        return route('candidats.index');
    }

    public function show(Candidat $candidat)
    {
        $candidat->load([
            'donneesPersonnelles',
            'representantsLegaux',
            'parcoursScolaire',
            'stages',
            'autresInscriptions',
            'compensations',
            'autorisationHorsCanton',
            'choixApprentissage',
            'fichiersJoints'
        ]);

        return route('candidats.show', ['candidat' => $candidat->id]);
    }

    public function submitForm()
    {
        $candidatId = session('candidat_id');
        $candidat = Candidat::findOrFail($candidatId);

        Candidat::where('id', $candidatId)->update(['token' => '', 'statut' => 'Candidature complète']);

        Mail::to($candidat->email)->send(new ConfirmationInscriptionMail());

        session()->forget('candidat_id');

        return redirect()->route('candidat-login')->with('success', 'formulaire soumis avec success');
    }
}
