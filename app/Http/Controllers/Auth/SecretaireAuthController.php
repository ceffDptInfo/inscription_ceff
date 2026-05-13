<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Secretaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretaireAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('secretaire')->attempt(['email' => $request->email, 'password' => $request->password])) {

            $request->session()->regenerate();

            return redirect('liste-candidats');
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('secretaire')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/secretaire-login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:secretaires',
            'password' => 'required|string',
        ]);

        $secretaire = new Secretaire();

        $secretaire->email = $request->email;
        $secretaire->password = bcrypt($request->password);

        $secretaire->save();

        Auth::guard('secretaire')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('secretaire-login')->with('success', 'Secretaire créé avec succès');
    }
}
