<?php

namespace Database\Seeders;

use App\Models\Push;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PushSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Push::factory(30)->create();
    }
}
