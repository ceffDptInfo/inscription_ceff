<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\DonneesPersonnelles;
use App\Models\FichierJoint;
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

        $fichierPermis = FichierJoint::where('candidat_id', $candidatId)->where('type_document', 'permis de séjour')->first();
        $fichierPhoto = FichierJoint::where('candidat_id', $candidatId)->where('type_document', 'photo portrait')->first();

        return inertia('candidat/DonneesPersonnelles', [
            'donnees' => $datas,
            'fichier_permis' => $fichierPermis ? $fichierPermis->nom_fichier : null,
            'fichier_photo' => $fichierPhoto ? $fichierPhoto->nom_fichier : null,
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
            Storage::disk('dossiers_inscription')->makeDirectory($dossierNom);
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

    public function edit($id)
    {
        $candidat = Candidat::with('donneesPersonnelles', 'representantsLegaux')->findOrFail($id);

        return inertia('secretaire/EditDonneesPersonnelles', [
            'candidat' => $candidat
        ]);
    }

    public function update(Request $request, $id)
    {
        $candidat = Candidat::findOrFail($id);

        $validatedPersonnelles = $request->validate([
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

        $candidat->donneesPersonnelles()->updateOrCreate(
            ['candidat_id' => $id],
            $validatedPersonnelles
        );

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

        $candidat->representantsLegaux()->updateOrCreate(
            ['candidat_id' => $id, 'ordre' => 1],
            array_merge($request->input('rep1'), ['candidat_id' => $id])
        );

        if ($request->boolean('has_second_rep')) {
            $candidat->representantsLegaux()->updateOrCreate(
                ['candidat_id' => $id, 'ordre' => 2],
                array_merge($request->input('rep2'), ['candidat_id' => $id])
            );
        } else {
            $candidat->representantsLegaux()->where('ordre', 2)->delete();
        }

        return redirect("/candidat-details/{$id}");
    }
}