<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'channel_type' => 1,
                'provider' => 1,
                'credentials' => '[]',
            ],
            [
                'user_id' => 1,
                'channel_type' => 1,
                'provider' => 2,
                'credentials' => '[]',
            ],
        ];

        DB::table('channels')->insert($data);
    }
}
