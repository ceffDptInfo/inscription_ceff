<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SecretaireController extends Controller
{
    public function index()
    {
        $candidats = Candidat::with('donneesPersonnelles')->get();

        Log::info($candidats);

        return inertia('secretaire/ListeCandidats', [
            'candidats' => $candidats,
        ]);
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|string',
        ]);

        $candidat = Candidat::findOrFail($id);
        $candidat->update([
            'statut' => $request->statut,
        ]);

        return back();
    }

    public function showCandidat()
    {
        return inertia('secretaire/CandidatDetails');
    }

    public function deleteCandidat($id)
    {
        $candidat = Candidat::findOrFail($id);

        $nomDossier = $candidat->dossier_nom;

        Storage::disk('public')->deleteDirectory($nomDossier);

        $candidat->delete();

        return back()->with('success', 'Candidat supprimé avec success');
    }
}
