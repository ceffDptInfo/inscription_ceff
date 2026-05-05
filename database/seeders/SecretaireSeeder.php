<?php

namespace Database\Seeders;

use App\Models\Secretaire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SecretaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Secretaire::create([
            'email' => 'industrie_info@ceff.ch',
            'password' => Hash::make('Pa$$w0rd')
        ]);
    }
}
