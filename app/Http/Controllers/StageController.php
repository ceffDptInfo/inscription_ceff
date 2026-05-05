<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'metier' => 'nullable|string|max:255',
            'entreprise' => 'nullable|string|max:255',
            'lieu' => 'nullable|string|max:255',
            'duree' => 'nullable|string|max:255',
        ]);

        $stage->update($validated);

        return response()->json(['message' => 'success'], 200);
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return response()->json(['message' => 'deleted'], 200);
    }
}
