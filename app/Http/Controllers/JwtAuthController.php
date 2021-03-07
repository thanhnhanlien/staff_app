<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class JwtAuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['getToken']]);
    }

    /**
     * Get a JWT via given credentials.
    */
    public function getToken(Request $request){
    	$req = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($req->fails()) {
            return response()->json($req->errors(), 422);
        }

        if (! $token = auth()->attempt($req->validated())) {
            return response()->json(['Auth error' => 'Unauthorized'], 401);
        }

        return $this->generateToken($token);
    }

    /**
     * Token refresh
    */
    public function refresh() {
        return $this->generateToken(auth()->refresh());
    }

    /**
     * Generate token
    */
    protected function generateToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    /**
     * User
    */
    public function employees(Request $request) {
    	$limit = 1500;
    	if(!empty($request->limit)){
    		$limit = $request->limit;
    	}
    	$manager = DB::table('users')
    				->join('departments', function ($join) {
            			$join->on('users.id', '=', 'departments.user_id');
			        })
			        ->select('email', 'users.name', 'phone', 'active');

    	$employee = DB::table('users')
            ->join('team_users', 'users.id', '=', 'team_users.user_id')
            ->join('teams', 'teams.id', '=', 'team_users.team_id')
            ->join('departments', 'departments.id', '=', 'teams.department_id')
            ->select('email', 'users.name', 'phone', 'active')
            ->union($manager)
            ->limit($limit)
            ->get();
        return response()->json([
        	'total' => count($employee),
        	'results' =>  $employee],  200);
    }
}