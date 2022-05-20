<?php

namespace App\Http\Controllers;

use App\Models\EarningsBase;
use Illuminate\Http\Request;

class EarningsBaseController extends Controller
{
    public function is_earningsBase($user_id)
    {
        return EarningsBase::where('user_id',$user_id)->exists();
    }

    public function get_earningsBase($user_id)
    {
        $earnings_base = EarningsBase::where('user_id',$user_id)->first();
        
        return $earnings_base->earnings_base;
    }
}