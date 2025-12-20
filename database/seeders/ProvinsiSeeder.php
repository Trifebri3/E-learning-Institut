<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    public function run(): void
    {
        $provinsi = [
            'Aceh','Sumatera Utara','Sumatera Barat','Riau','Jambi','Sumatera Selatan','Bengkulu',
            'Lampung','Kep. Bangka Belitung','Kep. Riau','DKI Jakarta','Jawa Barat','Jawa Tengah',
            'DI Yogyakarta','Jawa Timur','Banten','Bali','Nusa Tenggara Barat','Nusa Tenggara Timur',
            'Kalimantan Barat','Kalimantan Tengah','Kalimantan Selatan','Kalimantan Timur',
            'Kalimantan Utara','Sulawesi Utara','Sulawesi Tengah','Sulawesi Selatan',
            'Sulawesi Tenggara','Gorontalo','Sulawesi Barat','Maluku','Maluku Utara',
            'Papua','Papua Barat','Papua Tengah','Papua Pegunungan','Papua Selatan','Papua Barat Daya'
        ];

        foreach ($provinsi as $nama) {
            DB::table('provinsi')->insert(['nama' => $nama]);
        }
    }
}
