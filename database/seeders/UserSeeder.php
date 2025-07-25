<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users
        DB::table('users')->delete();
        
        $officerRole = Role::where('name', 'Wildfire Management Officer')->first();
        $firefighterRole = Role::where('name', 'Firefighter')->first();
        $ambulanceRole = Role::where('name', 'Ambulance Staff')->first();
        $residentRole = Role::where('name', 'Resident')->first();

        $users = [
            // Wildfire Management Officers
            [
                'name' => 'John Officer',
                'email' => 'officer@artemis.com',
                'password' => Hash::make('passwords10#'),
                'role_id' => $officerRole->id
            ],
            [
                'name' => 'Maria Rodriguez',
                'email' => 'officer2@artemis.com',
                'password' => Hash::make('passwory'),
                'role_id' => $officerRole->id
            ],
            
            // Firefighters
            [
                'name' => 'Mark Thompson',
                'email' => 'firefighter@artemis.com',
                'password' => Hash::make('passworu'),
                'role_id' => $firefighterRole->id
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'firefighter2@artemis.com',
                'password' => Hash::make('password77'),
                'role_id' => $firefighterRole->id
            ],
            [
                'name' => 'Robert Clarke',
                'email' => 'firefighter3@artemis.com',
                'password' => Hash::make('passwordt'),
                'role_id' => $firefighterRole->id
            ],
            
            // Ambulance Staff
            [
                'name' => 'Dr. Lisa Brown',
                'email' => 'ambulance@artemis.com',
                'password' => Hash::make('passwurd'),
                'role_id' => $ambulanceRole->id
            ],
            [
                'name' => 'Michael Davis',
                'email' => 'ambulance2@artemis.com',
                'password' => Hash::make('pass4word'),
                'role_id' => $ambulanceRole->id
            ],
            [
                'name' => 'Nurse Janet Smith',
                'email' => 'ambulance3@artemis.com',
                'password' => Hash::make('passw5ord'),
                'role_id' => $ambulanceRole->id
            ],
            
            // Residents
            [
                'name' => 'Jane Resident',
                'email' => 'resident@artemis.com',
                'password' => Hash::make('password123art-*'),
                'role_id' => $residentRole->id
            ],
            [
                'name' => 'David Johnson',
                'email' => 'resident2@artemis.com',
                'password' => Hash::make('pass5word'),
                'role_id' => $residentRole->id
            ],
            [
                'name' => 'Patricia Miller',
                'email' => 'resident3@artemis.com',
                'password' => Hash::make('pas557452sword'),
                'role_id' => $residentRole->id
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Users created successfully!');
    }
}