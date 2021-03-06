<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
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
