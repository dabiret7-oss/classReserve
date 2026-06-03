<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' =>'Dabire',
            'prenoms'=>'Aime',
            'email'=>'dabiret7@gmail.com',
            'password'=> Hash::make('admin1234'),
            'role'=>'admin',
            'statut'=>'valide',

        ]);
    }
}
