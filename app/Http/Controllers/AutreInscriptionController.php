<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AutreInscription;
use Illuminate\Http\Request;

class AutreInscriptionController extends Controller
{
    public function index()
    {
        $candidatId = session('candidat_id');
        $autresInscriptions = AutreInscription::where('candidat_id', $candidatId)->get();

        return inertia('candidat/AutresInscriptions', [
            'inscriptions' => $autresInscriptions,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');

        $validated = [];
        foreach ($request->input('inscriptions', []) as $inscription) {
            $validated['inscriptions'][] = [
                'etablissement' => $inscription['etablissement'] ?? null,
                'lieu' => $inscription['lieu'] ?? null,
            ];
        }

        AutreInscription::where('candidat_id', $candidatId)->delete();

        foreach ($validated['inscriptions'] as $inscriptionData) {
            if (count(array_filter($inscriptionData)) > 0) {
                $inscriptionData['candidat_id'] = $candidatId;
                AutreInscription::create($inscriptionData);
            }
        }

        return redirect()->route('informations')->with('success', 'Inscriptions enregistrés avec success');
    }
}
