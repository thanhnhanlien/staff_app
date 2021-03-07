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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unit()
    {
        // $user = User::factory()->count(1600)
        //         ->state([
        //             'active' => 1
        //         ])
        //         ->create();
        // $user = User::factory()->count(400)
        // ->create();
        // $departments = Department::factory()
        //     ->count(10)
        //     ->state(new Sequence(
        //         fn () => ['user_id' => User::pluck('id')->random()],
        //     ))
        //     ->create();
        // $team = Team::factory()
        //     ->count(15)
        //     ->state(new Sequence(
        //         fn () => ['department_id' => Department::pluck('id')->random()],
        //     ))
        //     ->create();
        $team_user = TeamUser::factory()
            ->count(20)
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

    /**
    * Get all employees.
    *
    * @return void
    */
    public function testGetEmployees()
    {
        $headers = ['Accept' => 'application/json'];

        $user = User::where('email', Config::get('api.apiEmail'))->first();
        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            $headers['Authorization'] = 'Bearer '.$token;
        }

        $response = $this->get(Config::get('app.url') . '/api/auth/employees', $headers);

        $response->assertStatus(200);
    }
}
