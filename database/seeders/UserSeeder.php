<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                'fname' => 'vajirali',
                'lname' => 'Asamadi',
                'email' => 'ali@gmail.com',
                'password' => '$2y$10$tbNteFMG/1d8WWLrQFSGAejznr5Kh3OuYxaEhVN7vdTm2Rjh.JxqG'                
            ]);
    }
}
