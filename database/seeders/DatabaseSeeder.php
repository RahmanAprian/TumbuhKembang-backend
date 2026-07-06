<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $adminId = DB::table('users')->insertGetId([
            'name'       => 'Admin Posyandu',
            'email'      => 'admin@tumbuhkembang.id',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Orang tua 1
        $parent1 = DB::table('users')->insertGetId([
            'name'       => 'Ibu Sari',
            'email'      => 'sari@gmail.com',
            'password'   => Hash::make('sari123'),
            'role'       => 'orangtua',
            'phone'      => '08123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Orang tua 2
        $parent2 = DB::table('users')->insertGetId([
            'name'       => 'Ibu Dewi',
            'email'      => 'dewi@gmail.com',
            'password'   => Hash::make('dewi123'),
            'role'       => 'orangtua',
            'phone'      => '08987654321',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Anak dari parent1
        $child1 = DB::table('children')->insertGetId([
            'user_id'    => $parent1,
            'name'       => 'Naura',
            'birth_date' => '2024-03-15',
            'gender'     => 'female',
            'blood_type' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $child2 = DB::table('children')->insertGetId([
            'user_id'    => $parent1,
            'name'       => 'Raka',
            'birth_date' => '2023-08-10',
            'gender'     => 'male',
            'blood_type' => 'B',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Anak dari parent2
        $child3 = DB::table('children')->insertGetId([
            'user_id'    => $parent2,
            'name'       => 'Zara',
            'birth_date' => '2024-09-01',
            'gender'     => 'female',
            'blood_type' => 'O',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Growth records
        $records = [
            [$child1, 3.2, 49.0, 32.0, 0,  '2024-03-15', 'normal'],
            [$child1, 5.7, 60.0, 38.0, 3,  '2024-06-15', 'normal'],
            [$child1, 7.2, 66.0, 41.0, 6,  '2024-09-15', 'normal'],
            [$child2, 3.5, 50.0, 33.0, 0,  '2023-08-10', 'normal'],
            [$child2, 8.0, 68.0, 43.0, 6,  '2024-02-10', 'normal'],
            [$child2, 9.8, 76.0, 46.0, 12, '2024-08-10', 'normal'],
            [$child3, 3.1, 48.0, 31.5, 0,  '2024-09-01', 'normal'],
            [$child3, 5.5, 59.0, 37.5, 3,  '2024-12-01', 'gizi_kurang'],
        ];

        foreach ($records as $r) {
            DB::table('growth_records')->insert([
                'child_id'             => $r[0],
                'weight'               => $r[1],
                'height'               => $r[2],
                'head_circumference'   => $r[3],
                'age_months'           => $r[4],
                'recorded_at'          => $r[5],
                'nutritional_status'   => $r[6],
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }

        // Default vaccines for child1
        $defaultVaccines = [
            ['HB-0',                   0],
            ['BCG',                    1],
            ['Polio 1',                1],
            ['DPT-HB-Hib 1',          2],
            ['Polio 2',                2],
            ['DPT-HB-Hib 2',          3],
            ['Polio 3',                3],
            ['DPT-HB-Hib 3',          4],
            ['Polio 4',                4],
            ['IPV',                    4],
            ['MR',                     9],
            ['DPT-HB-Hib 4 (Booster)',18],
            ['MR Booster',             18],
        ];

        foreach ([$child1, $child2, $child3] as $cid) {
            foreach ($defaultVaccines as [$name, $age]) {
                DB::table('vaccines')->insert([
                    'child_id'                => $cid,
                    'vaccine_name'            => $name,
                    'recommended_age_months'  => $age,
                    'is_done'                 => false,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);
            }
        }
    }
}
