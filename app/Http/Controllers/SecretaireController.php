<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Secretaire;
use Illuminate\Http\Request;

class SecretaireController extends Controller
{
    public function index()
    {
        return route('secretaires.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:secretaires,email',
            'password' => 'required|stringrs|min:6',
        ]);

        Secretaire::create([
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json([
            'message' => 'success',
        ], 201);
    }
}
