<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
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
            'name' => 'hanako',
            'email' => 'hanako0813@gmail.com',
            'password' => Hash::make('hanako0813'),
        ];
        DB::table('users')->insert($data);
    }
}
