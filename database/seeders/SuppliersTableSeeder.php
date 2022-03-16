<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $data = [
            'supp_code' => '10-100',
            'supp_name' => $faker->name,
            'supp_addr' => $faker->address,
            'supp_isActive' => 1,
            'supp_po_appr' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('suppliers')->insert($data);
    }
}
