<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AutorisationHorsCanton;
use App\Models\Compensations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InformationsController extends Controller
{

    public function index()
    {
        $candidatId = session('candidat_id');

        $compensation = Compensations::where('candidat_id', $candidatId)->first();
        $autorisation = AutorisationHorsCanton::where('candidat_id', $candidatId)->first();

        Log::info($compensation . ' ' . $autorisation);

        return inertia('candidat/Informations', [
            'compensation' => $compensation ? (bool) $compensation->reponse : false,
            'autorisation' => $autorisation ? (bool) $autorisation->reponse : false,
        ]);
    }
    public function store(Request $request)
    {
        $candidatId = session('candidat_id');

        $validated = $request->validate([
            'compensation_reponse' => 'required|boolean',
            'autorisation_reponse' => 'required|boolean',
        ]);

        $validated['candidat_id'] = $candidatId;

        Compensations::updateOrCreate(
            ['candidat_id' => $candidatId],
            ['reponse' => $validated['compensation_reponse']]
        );

        AutorisationHorsCanton::updateOrCreate(
            ['candidat_id' => $candidatId],
            ['reponse' => $validated['autorisation_reponse']]
        );

        return redirect()->route('choix-apprentissage')->with('success', 'donnees personnelles enregistrées');
    }
}
