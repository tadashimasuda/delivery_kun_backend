<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        return 'success';
    }
}
