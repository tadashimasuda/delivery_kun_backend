<?php

namespace App\Http\Controllers;

use App\Http\Requests\OAuthRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $request['password'] = bcrypt($request->password);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'prefecture_id' => 13,
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
        $earnings_base_controller = app()->make('App\Http\Controllers\EarningsBaseController');

        $user = $request->user();
        $user['access_token'] = $replace_access_token;
        $user['earnings_base'] = $user->prefecture->earnings_base;

        $is_earnings_base = $earnings_base_controller->is_earningsBase($user->id);

        if($is_earnings_base){
            $user['earnings_base'] = $earnings_base_controller->get_earningsBase($user->id);
        }

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request)
    {
        $earnings_base_controller = app()->make('App\Http\Controllers\EarningsBaseController');
        $user_id = $request->user()->id;
        $user = User::find($user_id);

        try{
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'vehicle_model' => $request->vehicleModelId,
                'prefecture_id' => $request->prefectureId
            ]);

            $earnings_base_controller->earning_base_updateOrCreate($user_id,$request->earningsBase);

            return \response()->json(['message'=>'success'],201);
        }catch(Exception $e){
            return \response()->json(['message'=>$e->getMessage()],500);
        }
    }

    public function OAuthLogin(OAuthRequest $request)
    {
        $provider_name = $request->providerName;
        $provider_id = $request->providerId;
        $user_name = $request->userName;
        $email = $request->email != null ? $request->email : '';
        $user_img = $request->userImg != null ? $request->userImg : '';

        return $this->accountFindOrCreate($provider_name,$provider_id,$user_name,$email,$user_img);
    }

    public function accountFindOrCreate($provider_name,$provider_id,$user_name,$email,$user_img)
    {
        $is_account = User::where([
                ['social_name',"=",$provider_name],
                ['social_id',"=",$provider_id],
            ])->first();

        if(!$is_account){
            $user = User::create([
                'name' => $user_name,
                'email' => $email,
                'social_name' => $provider_name,
                'social_id' => $provider_id,
                'img_path' => $user_img,
                'vehicle_model' => 1,
                'prefecture_id' => 13,
            ]);

            $user['access_token'] = $user->createToken('access_token')->accessToken;
            
            return new UserResource($user);
        }else{
            $is_account['access_token'] = $is_account->createToken('access_token')->accessToken;

            return new UserResource($is_account);
        }
    }

    public function userCount()
    {
        $user_count = User::count();

        return response()->json([
            'userCount' => $user_count
        ]);
    }
}