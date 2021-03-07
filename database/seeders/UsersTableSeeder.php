<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
			'email' => 'director@gmail.com',
			'password' => bcrypt('director'),
			'name' => 'Director',
			'phone' => '0946907587',
			'token' => 'Str::random(60)',
			'director' => 1,
			'active' => 1
		]);
    }
}
