<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as ProviderUser;

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
        $user_id = $request->user()->id;
        $user = User::find($user_id);

        try{
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'vehicle_model' => $request->vehicleModelId,
                'prefecture_id' => $request->prefectureId
            ]);

            return \response()->json(['message'=>'success'],201);
        }catch(Exception $e){
            return \response()->json(['message'=>$e->getMessage()],500);
        }
    }

    public function OAuthLogin(Request $request)
    {
        $providerName = $request->socialName;
        $access_token = $request->accessToken;

        try {
            $providerUser = Socialite::driver($providerName)->userFromToken($access_token);
        } catch (Exception $e) {
            return $e;
        }

        if($providerUser){
            return $this->accountFindOrCreate($providerUser,$providerName);
        }
    }

    public function accountFindOrCreate(ProviderUser $providerUser,$providerName)
    {
        $provider_id = $providerUser->getId();
        $provider_user_name = $providerUser->getName();
        $provider_user_email = $providerUser->getEmail();

        $is_account = User::where(
            [
                ['social_name',"=",$providerName],
                ['social_id',"=",$provider_id],
            ])->first();

        if(!$is_account){
            $user = User::create([
                'name' => $provider_user_name,
                'email' => $provider_user_email,
                'social_name' => $providerName,
                'social_id' => $provider_id,
                'vehicle_model' => 1,
                'prefecture_id' =>1,
            ]);

            $access_token = $user->createToken('access_token')->accessToken;
            
            $user['access_token'] = $access_token;
            
            return new UserResource($user);
        }else{
            $access_token = $is_account->createToken('access_token')->accessToken;
            $is_account['access_token'] = $access_token;

            return new UserResource($is_account);
        }
    }
}