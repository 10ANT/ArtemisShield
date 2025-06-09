<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Wildfire Management Officer',
                'permissions' => ['view_all_data', 'manage_resources', 'issue_orders', 'view_reports']
            ],
            [
                'name' => 'Firefighter',
                'permissions' => ['view_fire_data', 'submit_reports', 'request_help']
            ],
            [
                'name' => 'Ambulance Staff',
                'permissions' => ['view_medical_incidents', 'update_patient_status']
            ],
            [
                'name' => 'Resident',
                'permissions' => ['view_public_alerts', 'request_help']
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}