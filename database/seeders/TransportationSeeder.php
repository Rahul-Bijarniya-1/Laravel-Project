<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransportationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Customer::factory()->count(10)->create();
        \App\Models\Transporter::factory()->count(10)->create();
        \App\Models\User::factory()->count(20)->create();
        \App\Models\TripRequest::factory()->count(15)->create();
    }
}
