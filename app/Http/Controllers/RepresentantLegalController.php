<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RepresentantLegal;
use Illuminate\Http\Request;

class RepresentantLegalController extends Controller
{

    public function index()
    {
        $candidatId = session('candidat_id');

        $datas = RepresentantLegal::where('candidat_id', $candidatId)->get();

        return inertia('candidat/RepresentantsLegaux', [
            'reps' => $datas,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');

        $request->validate([
            'rep1.type_lien' => 'nullable|string',
            'rep1.nom' => 'nullable|string',
            'rep1.prenom' => 'nullable|string',
            'rep1.rue_et_num' => 'nullable|string',
            'rep1.npa_localite' => 'nullable|string',
            'rep1.tel_portable' => 'nullable|string',
            'rep1.email' => 'nullable|email',

            'has_second_rep' => 'boolean',

            'rep2.type_lien' => 'nullable|string',
            'rep2.nom' => 'nullable|string',
            'rep2.prenom' => 'nullable|string',
            'rep2.rue_et_num' => 'nullable|string',
            'rep2.npa_localite' => 'nullable|string',
            'rep2.tel_portable' => 'nullable|string',
            'rep2.email' => 'nullable|email',
        ]);

        $dataRep1 = array_merge($request->input('rep1'), ['candidat_id' => $candidatId]);

        RepresentantLegal::updateOrCreate(
            ['candidat_id' => $candidatId, 'ordre' => 1],
            $dataRep1
        );

        if ($request->boolean('has_second_rep')) {
            $dataRep2 = array_merge($request->input('rep2'), ['candidat_id' => $candidatId]);

            RepresentantLegal::updateOrCreate(
                ['candidat_id' => $candidatId, 'ordre' => 2],
                $dataRep2
            );
        } else {
            RepresentantLegal::where('candidat_id', $candidatId)
                ->where('ordre', 2)
                ->delete();
        }

        return redirect()->route('parcours-scolaire')->with('success', 'representants legaux enregistrés');
    }
}
