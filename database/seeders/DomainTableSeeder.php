<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'domain_code' => 'LMA',
            'domain_desc' => 'LMA',
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('domains')->insert($data);

        $data = [
            'domain_code' => 'HMP',
            'domain_desc' => 'Hamparan',
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('domains')->insert($data);
    }
}
