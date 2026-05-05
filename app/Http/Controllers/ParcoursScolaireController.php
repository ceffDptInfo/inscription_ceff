<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ParcoursScolaire;
use Illuminate\Http\Request;

class ParcoursScolaireController extends Controller
{

    public function index()
    {
        $candidatId = session('candidat_id');

        $datas = ParcoursScolaire::where('candidat_id', $candidatId)->first();

        return inertia('candidat/ParcoursScolaire', [
            'parcours' => $datas,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');

        $validated = $request->validate([
            'type_parcours' => 'nullable|string|max:255',
            'nom_ecole' => 'nullable|string|max:255',
            'lieu_ecole' => 'nullable|string|max:255',
            'niveau_francais' => 'nullable|string|max:255',
            'niveau_math' => 'nullable|string|max:255',
            'niveau_allemand' => 'nullable|string|max:255',
            'description_activite' => 'nullable|string',
        ]);

        $validated['candidat_id'] = $candidatId;

        ParcoursScolaire::updateOrCreate(['candidat_id' => $candidatId], $validated);

        return redirect()->route('stages')->with('success', 'parcours scolaire enregistré');
    }

    public function update(Request $request, ParcoursScolaire $parcoursScolaire)
    {
        $validated = $request->validate([
            'type_parcours' => 'nullable|string|max:255',
            'nom_ecole' => 'nullable|string|max:255',
            'lieu_ecole' => 'nullable|string|max:255',
            'niveau_francais' => 'nullable|string|max:255',
            'niveau_math' => 'nullable|string|max:255',
            'niveau_allemand' => 'nullable|string|max:255',
            'description_activite' => 'nullable|string',
        ]);

        $parcoursScolaire->update($validated);

        return response()->json(['message' => 'success'], 200);
    }
}
