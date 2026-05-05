<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
}
