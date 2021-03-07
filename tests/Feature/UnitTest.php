<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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


class UnitTest extends TestCase
{
    use RefreshDatabase;

    public function testData()
    {
        $this->seed();
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
                fn () => ['user_id' => User::pluck('id')->random()],
            ))
            ->create();
        $team = Team::factory()
            ->count(150)
            ->state(new Sequence(
                fn () => ['department_id' => Department::pluck('id')->random()],
            ))
            ->create();
        $team_user = TeamUser::factory()
            ->count(200)
            ->state(new Sequence(
                fn () => ['team_id' => Team::pluck('id')->random()],
            ))
            ->state(new Sequence(
                fn () => ['user_id' => User::pluck('id')->random()],
            ))
            ->create();
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testGetEmployees()
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
