<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $param = [
            'name' => 'admin',
            'email' => 'admin@admin.admin',
            'email_verified_at' => null,
            'password' => Hash::make('adminadmin'),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('admins')->insert($param);
    }
}
