<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ChoixApprentissage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChoixApprentissageController extends Controller
{
    public function index()
    {
        $candidatId = session('candidat_id');
        $choix = ChoixApprentissage::where('candidat_id', $candidatId)->first();

        Log::info($choix);

        $premier_choix = $choix ? $choix->premier_choix : null;
        $deuxieme_choix = $choix ? $choix->deuxieme_choix : null;

        return inertia('candidat/ChoixApprentissage', [
            'premier_choix' => $premier_choix,
            'deuxieme_choix' => $deuxieme_choix,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');

        $validated = $request->validate([
            'premier_choix' => 'nullable|string|max:255',
            'deuxieme_choix' => 'nullable|string|max:255',
        ]);

        ChoixApprentissage::updateOrCreate(
            ['candidat_id' => $candidatId],
            $validated
        );

        return redirect()->route('annexes')->with('success', 'Choix apprentissage enregistre avec success');
    }
}
