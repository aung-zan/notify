<?php

namespace Database\Seeders;

use App\Models\PushChannel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PushChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PushChannel::factory(30)->create();
    }
}
