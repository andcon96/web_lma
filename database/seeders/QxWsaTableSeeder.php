<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QxWsaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'wsas_url' => 'http://qad2017vm.ware:22079/wsa/wsatest',
            'wsas_domain' => '10USA',
            'wsas_path' => 'urn:iris.co.id:wsatest',
            'qx_enable' => 0,
            'qx_url' => null,
            'qx_path' => null
        ];

        DB::table('qxwsas')->insert($data);
    }
}
