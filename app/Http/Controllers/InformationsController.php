<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AutorisationHorsCanton;
use App\Models\Candidat;
use App\Models\Compensations;
use App\Models\FichierJoint;
use App\Services\FichierJointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InformationsController extends Controller
{
    protected $fichierJointService;

    public function __construct(FichierJointService $fichierJointService)
    {
        $this->fichierJointService = $fichierJointService;
    }

    public function index()
    {
        $candidatId = session('candidat_id');

        $compensation = Compensations::where('candidat_id', $candidatId)->first();
        $autorisation = AutorisationHorsCanton::where('candidat_id', $candidatId)->first();

        Log::info($compensation . ' ' . $autorisation);

        $fichierCompensation = FichierJoint::where('candidat_id', $candidatId)->where('type_document', 'mesures de compensations')->first();
        $fichierAutorisation = FichierJoint::where('candidat_id', $candidatId)->where('type_document', 'autorisation hors canton')->first();

        return inertia('candidat/Informations', [
            'compensation' => $compensation ? (bool) $compensation->reponse : false,
            'autorisation' => $autorisation ? (bool) $autorisation->reponse : false,
            'fichier_compensation' => $fichierCompensation ? $fichierCompensation->nom_fichier : null,
            'fichier_autorisation' => $fichierAutorisation ? $fichierAutorisation->nom_fichier : null,
        ]);
    }
    public function store(Request $request)
    {
        $candidatId = session('candidat_id');
        $candidat = Candidat::findOrFail($candidatId);

        $validated = $request->validate([
            'compensation_reponse' => 'required|boolean',
            'autorisation_reponse' => 'required|boolean',
            'document_compensation' => 'nullable|file',
            'document_autorisation' => 'nullable|file',
        ]);

        if ($validated['compensation_reponse']) {
            if ($request->hasFile('document_compensation')) {
                $this->fichierJointService->uploaderFichier($request->file('document_compensation'), $candidat, 'mesures de compensations');
            }
        } else {
            $fichierCompensation = FichierJoint::where('candidat_id', $candidatId)
                ->where('type_document', 'mesures de compensations')
                ->first();

            if ($fichierCompensation) {
                Storage::disk('dossiers_inscription')->delete($fichierCompensation->chemin_fichier);
                $fichierCompensation->delete();
            }
        }

        if ($validated['autorisation_reponse']) {
            if ($request->hasFile('document_autorisation')) {
                $this->fichierJointService->uploaderFichier($request->file('document_autorisation'), $candidat, 'autorisation hors canton');
            }
        } else {
            $fichierAutorisation = FichierJoint::where('candidat_id', $candidatId)
                ->where('type_document', 'autorisation hors canton')
                ->first();

            if ($fichierAutorisation) {
                Storage::disk('dossiers_inscription')->delete($fichierAutorisation->chemin_fichier);
                $fichierAutorisation->delete();
            }
        }

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

    public function edit($id)
    {
        $candidat = Candidat::with('autorisationHorsCanton', 'compensations')->findOrFail($id);

        return inertia('secretaire/EditInformations', [
            'candidat' => $candidat
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'compensation_reponse' => 'required|boolean',
            'autorisation_reponse' => 'required|boolean',
        ]);

        Compensations::updateOrCreate(
            ['candidat_id' => $id],
            ['reponse' => $validated['compensation_reponse']]
        );

        AutorisationHorsCanton::updateOrCreate(
            ['candidat_id' => $id],
            ['reponse' => $validated['autorisation_reponse']]
        );

        return redirect("/candidat-details/{$id}");
    }
}
