<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\FichierJoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FichierJointController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'candidat_id' => 'required|exists:candidats,id',
            'type_document' => 'required|string|max:255',
            'fichier' => 'required|file',
        ]);

        $candidat = Candidat::find($validated['candidat_id']);
        $path = $request->file('fichier')->store($candidat->dossier_nom);

        FichierJoint::create([
            'candidat_id' => $validated['candidat_id'],
            'type_document' => $validated['type_document'],
            'nom_fichier' => $request->file('fichier')->getClientOriginalName(),
            'chemin_ficher' => $path,
        ]);

        return response()->json(['message' => 'success'], 201);
    }

    public function update(Request $request, FichierJoint $fichierJoint)
    {
        $validated = $request->validate([
            'type_document' => 'required|string|max:255',
            'fichier' => 'nullable|file',
        ]);

        Storage::delete($fichierJoint->chemin_ficher);

        $candidat = Candidat::find($fichierJoint->candidat_id);
        $path = $request->file('fichier')->store($candidat->dossier_nom);

        $fichierJoint->update([
            'type_document' => $validated['type_document'],
            'nom_fichier' => $request->file('fichier')->getClientOriginalName(),
            'chemin_ficher' => $path,
        ]);

        return response()->json(['message' => 'success'], 200);
    }

    public function destroy(FichierJoint $fichierJoint)
    {
        Storage::delete($fichierJoint->chemin_ficher);
        $fichierJoint->delete();

        return response()->json(['message' => 'deleted'], 200);
    }
}
