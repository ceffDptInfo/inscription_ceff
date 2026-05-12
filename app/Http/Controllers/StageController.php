<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AutreInscription;
use App\Models\Candidat;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StageController extends Controller
{
    public function index()
    {
        $candidatId = session('candidat_id');

        $datas = Stage::where('candidat_id', $candidatId)->get();

        return inertia('candidat/Stages', [
            'stages' => $datas,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');

        Log::info($request);

        $validated = [];
        foreach ($request->input('stages', []) as $stage) {
            $validated['stages'][] = [
                'metier' => $stage['metier'] ?? null,
                'entreprise' => $stage['entreprise'] ?? null,
                'lieu' => $stage['lieu'] ?? null,
                'duree' => $stage['duree'] ?? null,
            ];
        }

        Stage::where('candidat_id', $candidatId)->delete();

        foreach ($validated['stages'] as $stageData) {
            if (count(array_filter($stageData)) > 0) {
                $stageData['candidat_id'] = $candidatId;
                Stage::create($stageData);
            }
        }

        return redirect()->route('autres-inscriptions')->with('success', 'stages enregistrés');
    }

    public function edit($id)
    {
        $candidat = Candidat::with('stages', 'autresInscriptions')->findOrFail($id);

        return inertia('secretaire/EditStages', [
            'candidat' => $candidat
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedStages = [];
        foreach ($request->input('stages', []) as $stage) {
            $validatedStages[] = [
                'metier' => $stage['metier'] ?? null,
                'entreprise' => $stage['entreprise'] ?? null,
                'lieu' => $stage['lieu'] ?? null,
                'duree' => $stage['duree'] ?? null,
            ];
        }

        Stage::where('candidat_id', $id)->delete();

        foreach ($validatedStages as $stageData) {
            if (count(array_filter($stageData)) > 0) {
                $stageData['candidat_id'] = $id;
                Stage::create($stageData);
            }
        }

        $validatedInscriptions = [];
        foreach ($request->input('inscriptions', []) as $inscription) {
            $validatedInscriptions[] = [
                'etablissement' => $inscription['etablissement'] ?? null,
                'lieu' => $inscription['lieu'] ?? null,
            ];
        }

        AutreInscription::where('candidat_id', $id)->delete();

        foreach ($validatedInscriptions as $inscriptionData) {
            if (count(array_filter($inscriptionData)) > 0) {
                $inscriptionData['candidat_id'] = $id;
                AutreInscription::create($inscriptionData);
            }
        }

        return redirect("/candidat-details/{$id}");
    }
}
