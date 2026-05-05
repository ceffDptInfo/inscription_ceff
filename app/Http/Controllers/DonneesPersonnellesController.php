<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\DonneesPersonnelles;
use App\Services\FichierJointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;


class DonneesPersonnellesController extends Controller
{
    protected $fichierJointService;

    public function __construct(FichierJointService $fichierJointService)
    {
        $this->fichierJointService = $fichierJointService;
    }

    public function index()
    {
        $candidatId = session('candidat_id');

        $datas = DonneesPersonnelles::where('candidat_id', $candidatId)->first();

        return inertia('candidat/DonneesPersonnelles', [
            'donnees' => $datas,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');
        $candidat = Candidat::findOrFail($candidatId);

        $validated = $request->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'rue_et_num' => 'nullable|string|max:255',
            'npa_localite' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'langue_maternelle' => 'nullable|string|max:255',
            'no_avs' => 'nullable|string|max:255',
            'tel_fixe' => 'nullable|string|max:255',
            'tel_portable' => 'nullable|string|max:255',
            'email_prive' => 'nullable|email|max:255',
            'genre' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:255',
            'pays_origine' => 'nullable|string|max:255',
            'type_permis' => 'nullable|string|max:255',
            'validite_permis' => 'nullable|date',
            'remarques' => 'nullable|string',
            'document_permis' => 'nullable|file',
            'photo_portrait' => 'nullable|file',
        ]);

        $dossierNom = '';

        if (empty($candidat->dossier_nom)) {
            $dossierNom = Str::slug($validated['nom'] . '_' . $validated['prenom'] . '_' . now()->format('Y-m-d_H-i-s'));
            Storage::disk('public')->makeDirectory($dossierNom);
            $candidat->update(['dossier_nom' => $dossierNom]);
        }

        if ($request->hasFile('document_permis')) {
            $this->fichierJointService->uploaderFichier($request->file('document_permis'), $candidat, 'permis de séjour');
        }

        if ($request->hasFile('photo_portrait')) {
            $this->fichierJointService->uploaderFichier($request->file('photo_portrait'), $candidat, 'photo portrait');
        }

        $donnees = collect($validated)->except(['document_permis', 'photo_portrait'])->toArray();
        $donnees['candidat_id'] = $candidatId;

        DonneesPersonnelles::updateOrCreate(
            ['candidat_id' => $candidatId],
            $donnees
        );

        return redirect()->route('representants-legaux')->with('success', 'donnees personnelles enregistrées');
    }

    public function update(DonneesPersonnelles $donneesPersonnelles)
    {
        $validated = request()->validate([
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'rue_et_num' => 'nullable|string|max:255',
            'npa_localite' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date',
            'langue_maternelle' => 'nullable|string|max:255',
            'no_avs' => 'nullable|string|max:255',
            'tel_fixe' => 'nullable|string|max:255',
            'tel_portable' => 'nullable|string|max:255',
            'email_prive' => 'nullable|email|max:255',
            'genre' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:255',
            'pays_origine' => 'nullable|string|max:255',
            'type_permis' => 'nullable|string|max:255',
            'validite_permis' => 'nullable|date',
            'remarques' => 'nullable|string',
        ]);

        $donneesPersonnelles->update($validated);

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
