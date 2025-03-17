<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'IDM',
                'barang_nama' => 'Indomie',
                'harga_beli' => 2000,
                'harga_jual' => 2500,
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 2,
                'barang_kode' => 'CCL',
                'barang_nama' => 'Coca Cola',
                'harga_beli' => 4000,
                'harga_jual' => 5000,
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 3,
                'barang_kode' => 'LFB',
                'barang_nama' => 'Lifeboy',
                'harga_beli' => 4500,
                'harga_jual' => 6000,
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 4,
                'barang_kode' => 'BTR',
                'barang_nama' => 'Baterai',
                'harga_beli' => 3000,
                'harga_jual' => 5000,
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 5,
                'barang_kode' => 'BRS',
                'barang_nama' => 'Beras',
                'harga_beli' => 50000,
                'harga_jual' => 70000,
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 1,
                'barang_kode' => 'CTT',
                'barang_nama' => 'Chitato',
                'harga_beli' => 6000,
                'harga_jual' => 7000,
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 2,
                'barang_kode' => 'TEH',
                'barang_nama' => 'Fruit Tea',
                'harga_beli' => 2000,
                'harga_jual' => 3000,
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3,
                'barang_kode' => 'PSD',
                'barang_nama' => 'Pepsodent',
                'harga_beli' => 7000,
                'harga_jual' => 10000,
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 4,
                'barang_kode' => 'LMP',
                'barang_nama' => 'Lampu',
                'harga_beli' => 10000,
                'harga_jual' => 12000,
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5,
                'barang_kode' => 'GLA',
                'barang_nama' => 'GULA',
                'harga_beli' => 15000,
                'harga_jual' => 20000,
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
