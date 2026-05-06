<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\FichierJoint;
use App\Models\ParcoursScolaire;
use App\Services\FichierJointService;
use Illuminate\Http\Request;

class ParcoursScolaireController extends Controller
{
    protected $fichierJointService;

    public function __construct(FichierJointService $fichierJointService)
    {
        $this->fichierJointService = $fichierJointService;
    }

    public function index()
    {
        $candidatId = session('candidat_id');

        $datas = ParcoursScolaire::where('candidat_id', $candidatId)->first();

        $bulletin = FichierJoint::where('candidat_id', $candidatId)->where('type_document', 'bulletin scolaire')->first();
        $cv = FichierJoint::where('candidat_id', $candidatId)->where('type_document', 'CV')->first();

        return inertia('candidat/ParcoursScolaire', [
            'parcours' => $datas,
            'bulletin' => $bulletin ? $bulletin->nom_fichier : null,
            'cv' => $cv ? $cv->nom_fichier : null,
        ]);
    }

    public function store(Request $request)
    {
        $candidatId = session('candidat_id');
        $candidat = Candidat::findOrFail($candidatId);

        $validated = $request->validate([
            'type_parcours' => 'nullable|string|max:255',
            'nom_ecole' => 'nullable|string|max:255',
            'lieu_ecole' => 'nullable|string|max:255',
            'niveau_francais' => 'nullable|string|max:255',
            'niveau_math' => 'nullable|string|max:255',
            'niveau_allemand' => 'nullable|string|max:255',
            'description_activite' => 'nullable|string',
            'cv' => 'nullable|file',
            'bulletin_scolaire' => 'nullable|file',
        ]);

        if ($request->hasFile('cv')) {
            $this->fichierJointService->uploaderFichier($request->file('cv'), $candidat, 'CV');
        }
        if ($request->hasFile('bulletin_scolaire')) {
            $this->fichierJointService->uploaderFichier($request->file('bulletin_scolaire'), $candidat, 'bulletin scolaire');
        }

        $donnees = collect($validated)->except(['cv', 'bulletin_scolaire'])->toArray();
        $donnees['candidat_id'] = $candidatId;

        ParcoursScolaire::updateOrCreate(['candidat_id' => $candidatId], $donnees);

        return redirect()->route('stages')->with('success', 'parcours scolaire enregistré');
    }
}
