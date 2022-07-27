<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PsEndpoint;
use Illuminate\Database\Seeder;
use Database\Seeders\EndpointSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EndpointSeeder::class,
        ]);
    }
}
