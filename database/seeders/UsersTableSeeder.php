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
        $directorEmail = 'director@gmail.com';
        $director = User::where('email', '=', $directorEmail)->first();
        if (!$director) {
            User::create([
    			'email' => $directorEmail,
    			'password' => bcrypt('director'),
    			'name' => 'Director',
    			'phone' => '0946907587',
    			'token' => '',
    			'director' => 1,
    			'active' => 1
    		]);
        }
    }
}
