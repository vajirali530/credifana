<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ccDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cc_details')->insert([
        	[
                'name' => 'VISA',
                'apr' => '08-15',
                'cashback' => '1',
                'image' => 'visa.png',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        	[
                'name' => 'MASTER CARD',
                'apr' => '06-08',
                'cashback' => '5',
                'image' => 'mastercard.png',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        	[
                'name' => 'RuPay',
                'apr' => '11-17',
                'cashback' => '1',
                'image' => 'rupay.png',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        	[
                'name' => 'American express',
                'apr' => '12-21',
                'cashback' => '2',
                'image' => 'americanexpress.png',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
