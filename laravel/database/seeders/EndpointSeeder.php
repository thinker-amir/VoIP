<?php

namespace Database\Seeders;

use App\Models\PsAor;
use App\Models\PsAuth;
use App\Models\PsEndpoint;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EndpointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create extensions from 1000 to 7000
        PsEndpoint::factory(6000)->create();
        $endpoints = PsEndpoint::all();

        foreach ($endpoints as $endpoint) {
            PsAuth::factory()->create([
                'id' => $endpoint->id,
                'auth_type' => 'userpass',
                'password' => $endpoint->id,
                'username' => $endpoint->id,
            ]);
            PsAor::factory()->create([
                'id' => $endpoint->id,
                'max_contacts' => 1,
            ]);
        }

        // Create sipp extensions for testing concurrent calls
        PsEndpoint::factory()->create([
            'id' => 'sipp',
            'aors' => null,
            'auth' => null,
        ]);

    }
}
