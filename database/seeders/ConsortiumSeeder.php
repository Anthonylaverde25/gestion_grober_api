<?php

namespace Database\Seeders;

use App\Models\Consortium;
use Illuminate\Database\Seeder;

class ConsortiumSeeder extends Seeder
{
    public function run(): void
    {
        Consortium::create(['name' => 'Cattorini']);
    }
}
