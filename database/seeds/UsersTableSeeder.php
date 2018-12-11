<?php

use Illuminate\Database\Seeder;
use Illumiate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@benfrog.net',
            'password' => password_hash('secret', PASSWORD_DEFAULT)
        ]);
    }
}
