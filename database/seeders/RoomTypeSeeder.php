<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'TypeName' => 'Standard',
                'Description' => 'Phòng tiêu chuẩn',
                'Capacity' => 2
            ],
            [
                'TypeName' => 'Superior',
                'Description' => 'Phòng cao cấp',
                'Capacity' => 2
            ],
            [
                'TypeName' => 'Deluxe',
                'Description' => 'Phòng deluxe',
                'Capacity' => 3
            ],
            [
                'TypeName' => 'Villa',
                'Description' => 'Biệt thự',
                'Capacity' => 4
            ]
        ];

        foreach ($types as $type) {
            DB::table('room_type')->insert($type);
        }
    }
}