<?php

namespace Database\Seeders;

use App\Models\Master\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleType = ['Super_User', 'SPV', 'Admin'];

        for($i = 0; $i < count($roleType); $i++)
        {
            $data[$i] = [
                'role_type' => $roleType[$i],
                'role_id' => Role::all()->random()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('role_types')->insert($data);
    }
}
