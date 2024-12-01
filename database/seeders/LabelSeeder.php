<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_label')->insert([
            [
                'name' => 'bug'
            ],
            [
                'name' => 'question'
            ],
            [
                'name' => 'enhancement'
            ]
        ]);
    }
}
