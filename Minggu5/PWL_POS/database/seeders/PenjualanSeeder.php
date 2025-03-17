<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1, 'user_id' => 1, 'pembeli' => "Alex",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-03-01',
            ],
            [
                'penjualan_id' => 2, 'user_id' => 2, 'pembeli' => "Budi",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-03-01',
            ],
            [
                'penjualan_id' => 3, 'user_id' => 3, 'pembeli' => "Ridwan",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-03-01',
            ],
            [
                'penjualan_id' => 4, 'user_id' => 1, 'pembeli' => "Geri",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-28',
            ],
            [
                'penjualan_id' => 5, 'user_id' => 2, 'pembeli' => "Dani",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-28',
            ],
            [
                'penjualan_id' => 6, 'user_id' => 3, 'pembeli' => "Maman",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-28',
            ],
            [
                'penjualan_id' => 7, 'user_id' => 1, 'pembeli' => "Putri",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-20',
            ],
            [
                'penjualan_id' => 8, 'user_id' => 2, 'pembeli' => "Caca",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-20',
            ],
            [
                'penjualan_id' => 9, 'user_id' => 3, 'pembeli' => "Gaby",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-20',
            ],
            [
                'penjualan_id' => 10, 'user_id' => 1, 'pembeli' => "Angel",
                'penjualan_kode' => Str::random(5), 'penjualan_tanggal' => '2025-02-15',
            ],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
