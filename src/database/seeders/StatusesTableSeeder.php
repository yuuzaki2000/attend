<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            'id' => 1,
            'user_id' => 1,
            'date' => Carbon::parse('2025-09-01'),
            'content' => '勤務中',
        ];
        DB::table('statuses')->insert($data);
        $data = [
            'id' => 2,
            'user_id' => 2,
            'date' => Carbon::parse('2025-09-01'),
            'content' => '休憩中',
        ];
        DB::table('statuses')->insert($data);
        $data = [
            'id' => 3,
            'user_id' => 3,
            'date' => Carbon::parse('2025-09-01'),
            'content' => '勤務外',
        ];
        DB::table('statuses')->insert($data);
        $data = [
            'id' => 4,
            'user_id' => 4,
            'date' => Carbon::parse('2025-09-01'),
            'content' => '退勤済',
        ];
        DB::table('statuses')->insert($data);
    }
}
