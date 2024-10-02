<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('trados')->insert([
            ['name' => 'Trado Kecil', 'type' => 'Kecil', 'capacity' => 10, 'price' => 200000, 'available_quantity' => 5],
            ['name' => 'Trado Besar', 'type' => 'Besar', 'capacity' => 20, 'price' => 300000, 'available_quantity' => 3],
            ['name' => 'Trado Medium', 'type' => 'Medium', 'capacity' => 15, 'price' => 250000, 'available_quantity' => 8],
        ]);
    }
}
