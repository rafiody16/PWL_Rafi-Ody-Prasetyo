<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_kode' => 'UNL',
                'supplier_nama' => 'Unilever',
                'supplier_alamat' => 'Pasuruan',
            ],
            [
                'supplier_kode' => 'MYR',
                'supplier_nama' => 'Mayora',
                'supplier_alamat' => 'Surabaya',
            ],
            [
                'supplier_kode' => 'KLB',
                'supplier_nama' => 'Kalbe',
                'supplier_alamat' => 'Pandaan',
            ]
        ];
        DB::table('m_supplier')->insert($data);
    }
}
