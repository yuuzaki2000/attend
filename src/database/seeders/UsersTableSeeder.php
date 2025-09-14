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
        $data = [
            'id' => 2,
            'name' => 'jack',
            'email' => 'jack0813@gmail.com',
            'password' => Hash::make('jack0813'),
        ];
        DB::table('users')->insert($data);
        $data = [
            'id' => 3,
            'name' => 'tomy',
            'email' => 'tomy0813@gmail.com',
            'password' => Hash::make('tomy0813'),
        ];
        DB::table('users')->insert($data);
        $data = [
            'id' => 4,
            'name' => 'mary',
            'email' => 'mary0813@gmail.com',
            'password' => Hash::make('mary0813'),
        ];
        DB::table('users')->insert($data);
    }
}
