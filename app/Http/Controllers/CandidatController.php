<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmationInscriptionMail;
use App\Models\Candidat;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Mail;

class CandidatController extends Controller
{
    public function index()
    {
        return route('candidats.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:candidats,email',
        ]);

        $token = Str::random(64);

        $candidat = Candidat::create([
            'email' => $validated['email'],
            'token' => $token,
            'date_creation' => now(),
            'statut' => 'Nouveau candidat',
        ]);

        return response()->json([
            'message' => 'success',
        ], 201);
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
