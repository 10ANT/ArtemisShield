<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $officerRole = Role::where('name', 'Wildfire Management Officer')->first();
        
        User::create([
            'name' => 'John Officer',
            'email' => 'officer@artemis.com',
            'password' => Hash::make('password'),
            'role_id' => $officerRole->id
        ]);
    }
}