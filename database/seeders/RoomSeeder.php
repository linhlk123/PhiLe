<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $roomTypes = DB::table('room_type')->get();
        
        foreach ($roomTypes as $type) {
            $numRooms = ($type->TypeName === 'Villa') ? 50 : 40;
            
            for ($i = 1; $i <= $numRooms; $i++) {
                $floorNumber = ceil($i / 10);
                $roomNumber = sprintf("%d%02d", $floorNumber, $i % 10 ?: 10);
                
                DB::table('rooms')->insert([
                    'RoomNumber' => $type->TypeName === 'Villa' ? "V" . $roomNumber : $roomNumber,
                    'RoomTypeID' => $type->RoomTypeID,
                    'Status' => 'Available',
                    'Single_Bed' => $type->TypeName === 'Standard' ? 2 : 1,
                    'Double_Bed' => $type->TypeName === 'Standard' ? 0 : 1,
                ]);
            }
        }
    }
}