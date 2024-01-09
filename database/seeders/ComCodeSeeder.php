<?php

namespace Database\Seeders;

use App\Models\ComCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('com_codes')->truncate();
        $data = [
            ['code_cd' => 'STATUS_KARYAWAN_01', 'code_nm' => 'Karyawan Tetap', 'code_group' => 'STATUS_KARYAWAN', 'code_value' => ''],
            ['code_cd' => 'STATUS_KARYAWAN_02', 'code_nm' => 'Karyawan Tidak Tetap', 'code_group' => 'STATUS_KARYAWAN', 'code_value' => ''],
            ['code_cd' => 'GAJI_01', 'code_nm' => 'Bulan', 'code_group' => 'STATUS_GAJI', 'code_value' => ''],
            ['code_cd' => 'GAJI_02', 'code_nm' => 'Harian', 'code_group' => 'STATUS_GAJI', 'code_value' => ''],
            ['code_cd' => 'STATUS_AGENT_01', 'code_nm' => 'Agent Tetap', 'code_group' => 'STATUS_AGENT', 'code_value' => ''],
            ['code_cd' => 'STATUS_AGENT_02', 'code_nm' => 'Agent Tidak Tetap', 'code_group' => 'STATUS_AGENT', 'code_value' => ''],
        ];

        foreach ($data as $dt) {
            ComCode::create($dt);
        }
    }
}
