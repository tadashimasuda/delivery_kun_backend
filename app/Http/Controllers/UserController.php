<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $request['password'] = bcrypt($request->password);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'prefecture_id' => 1,
            'vehicle_model' => 0
        ]);

        $access_token = $user->createToken('access_token')->accessToken;

        $user['access_token'] = $access_token;

        return new UserResource($user);
    }

    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $access_token = $user->createToken('access_token')->accessToken;
            $user['access_token'] = $access_token;

            return new UserResource($user);
        }
        return response([
            'message' => '認証に失敗しました。'
        ], 401);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        
        return \response()->json(
            ['message' => 'success']
        );
    }

    public function user(Request $request)
    {
        $access_token = $request->header('Authorization');
        $replace_access_token = str_replace('Bearer ', '', $access_token);

        $user = $request->user();
        $user['access_token'] = $replace_access_token;

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request)
    {
        return 'as';
    }
}