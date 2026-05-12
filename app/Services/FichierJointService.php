<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Models\Candidat;
use App\Models\FichierJoint;

class FichierJointService
{
    public function uploaderFichier(UploadedFile $fichier, Candidat $candidat, string $typeDocument)
    {
        $dossierNom = $candidat->dossier_nom;

        $nomOriginal = $fichier->getClientOriginalName();

        $chemin = $fichier->storeAs($dossierNom, $nomOriginal, 'dossiers_inscription');

        FichierJoint::updateOrCreate(
            ['candidat_id' => $candidat->id, 'type_document' => $typeDocument],
            ['nom_fichier' => $nomOriginal, 'chemin_fichier' => $chemin]
        );
    }
}