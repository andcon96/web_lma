<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departmentCode = ['PT', 'R&D', 'FIN', 'IT', 'Conslt.', 'Admin'];
        $departmentName = [
            'Perseroran', 'Research and Development', 'Finance',
            'IT Department', 'Consultant', 'Administrator'
        ];

        for($i = 0; $i < count($departmentCode); $i++) {
            $data[$i] = [
                'department_code' => $departmentCode[$i],
                'department_name' => $departmentName[$i]
            ];
        }
        DB::table('departments')->insert($data);
    }
}
