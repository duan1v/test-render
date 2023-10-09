<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            'age'        => random_int(10, 30),
            'type'       => random_int(0, 5),
            'name'       => Str::random(10),
            'country'    => Str::random(5),
            'job'        => Str::random(7),
            'brief'      => Str::random(100),
            'email'      => Str::random(10) . '@gmail.com',
            'created_at' => now(),
            'updated_at' => now(),
            //            'password' => Hash::make('password'),
        ]);
    }
}
