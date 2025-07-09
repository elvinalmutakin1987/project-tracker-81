<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Work_type;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Work_type::insert(
            [
                ['work_name' => 'CCTV'],
                ['work_name' => 'Intrusion Alarm'],
                ['work_name' => 'Fire Alarm'],
                ['work_name' => 'Time & Attendance'],
                ['work_name' => 'Access Controll'],
                ['work_name' => 'Automatic Border Controll'],
                ['work_name' => 'Flap/Tripod/Barrier Gate'],
                ['work_name' => 'Parking System'],
                ['work_name' => 'Guard Partrol'],
                ['work_name' => 'Video Door Phone'],
                ['work_name' => 'Parimeter Security'],
                ['work_name' => 'VMS'],
                ['work_name' => 'PAVA'],
                ['work_name' => 'Video Wall'],
                ['work_name' => 'Office Network'],
                ['work_name' => 'Data Center'],
                ['work_name' => 'PABX'],
                ['work_name' => 'Solar Panel'],
            ]
        );
    }
}
