<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opds = [
            ['kode_opd' => '1.01.01', 'nama_opd' => 'Dinas Pendidikan', 'singkatan' => 'Disdik'],
            ['kode_opd' => '1.02.01', 'nama_opd' => 'Dinas Kesehatan', 'singkatan' => 'Dinkes'],
            ['kode_opd' => '1.03.01', 'nama_opd' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak', 'singkatan' => 'DP3A'],
            ['kode_opd' => '1.04.01', 'nama_opd' => 'Dinas Kependudukan dan Pencatatan Sipil', 'singkatan' => 'Disdukcapil'],
            ['kode_opd' => '1.05.01', 'nama_opd' => 'Dinas Sosial', 'singkatan' => 'Dinsos'],
            ['kode_opd' => '1.06.01', 'nama_opd' => 'Dinas Pemuda dan Olahraga', 'singkatan' => 'Dispora'],
            ['kode_opd' => '1.07.01', 'nama_opd' => 'Dinas Perpustakaan dan Kearsipan', 'singkatan' => 'Disperpusip'],
            ['kode_opd' => '1.08.01', 'nama_opd' => 'Dinas Komunikasi dan Informatika', 'singkatan' => 'Diskominfo'],
        ];

        foreach ($opds as $opd) {
            Opd::updateOrCreate(
                ['kode_opd' => $opd['kode_opd']],
                $opd + ['is_active' => true],
            );
        }
    }
}
