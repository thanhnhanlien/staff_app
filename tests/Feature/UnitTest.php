<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Department;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Artisan;


class UnitTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

        // This will seed your database
        Artisan::call('db:seed');
    }

    public function testInsertData()
    {
        $user = User::factory()->count(1600)
                ->state([
                    'active' => 1
                ])
                ->create();
        $user = User::factory()->count(400)
        ->create();
        $departments = Department::factory()
            ->count(100)
            ->state(new Sequence(
                fn () => ['user_id' => rand(1,User::count())],
            ))
            ->create();
        $team = Team::factory()
            ->count(150)
            ->state(new Sequence(
                fn () => ['department_id' => rand(1,Department::count())],
            ))
            ->create();
        $team_user = TeamUser::factory()
            ->count(200)
            ->state(new Sequence(
                fn () => ['team_id' => rand(1,Team::count())],
            ))
            ->state(new Sequence(
                fn () => ['user_id' => rand(1,User::count())],
            ))
            ->create();
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testApiGetEmployees()
    {
        $headers = ['Accept' => 'application/json'];

        $user = User::where('email', Config::get('api.apiEmail'))->first();
        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            $headers['Authorization'] = 'Bearer '.$token;
        }

        $response = $this->get('/api/auth/employees', $headers);

        $response->assertStatus(200);
    }
}
