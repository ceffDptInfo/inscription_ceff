<?php

namespace App\Http\Controllers;

use App\Models\Candidat;

class AnnexesController extends Controller
{
    public function index()
    {
        $candidatId = session('candidat_id');

        $candidat = Candidat::with([
            'donneesPersonnelles',
            'parcoursScolaire',
            'compensations',
            'autorisationHorsCanton',
            'fichiersJoints'
        ])->findOrFail($candidatId);

        $fichiers = $candidat->fichiersJoints;
        $fichiersNoms = $fichiers->pluck('type_document')->toArray();

        $documentsAttendus = [];
        $documentsManquants = [];

        $documentsAttendus['photo portrait'] = "photo portrait";

        if ($candidat->donneesPersonnelles && $candidat->donneesPersonnelles->nationalite === 'Autre') {
            $documentsAttendus['permis de séjour'] = "permis de séjour";
        }
        if ($candidat->parcoursScolaire && $candidat->parcoursScolaire->type_parcours === 'Canton de Berne') {
            $documentsAttendus['bulletin scolaire'] = "bulletin scolaire";
        }

        if ($candidat->parcoursScolaire && $candidat->parcoursScolaire->type_parcours === 'Autre activite') {
            $documentsAttendus['CV'] = "CV";
        }

        if ($candidat->compensations && $candidat->compensations->reponse) {
            $documentsAttendus['mesures de compensations'] = "mesures de compensations";
        }

        if ($candidat->autorisationHorsCanton && $candidat->autorisationHorsCanton->reponse) {
            $documentsAttendus['autorisation hors canton'] = "autorisation hors canton";
        }

        foreach ($documentsAttendus as $type => $label) {
            if (!in_array($type, $fichiersNoms)) {
                $documentsManquants[] = $label;
            }
        }

        $fichiersFormates = $fichiers->map(function ($fichier) {
            return [
                'nom_fichier' => $fichier->nom_fichier,
                'description' => $fichier->type_document,
            ];
        });

        return inertia('candidat/Annexes', [
            'annexes' => $fichiersFormates,
            'documents_manquants' => $documentsManquants,
        ]);
    }
}
