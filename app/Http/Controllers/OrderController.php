<?php

namespace App\Http\Controllers;

use App\Models\OrderDemaecan;
use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = User::with('prefecture')->find($request->user()->id);

        $earnings_incentive = $request->earnings_incentive;
        $earnings_base = $user->prefecture->earnings_base;
        $earnings_total = $earnings_incentive * $earnings_base;
        $user_id = $request->user()->id;
        $prefecture_id = $user->prefecture_id;
        $status_controller = app()->make('App\Http\Controllers\StatusController');

        if ($status_controller->is_status($user_id)) {
            DB::transaction(function () use($user_id,$earnings_base,$earnings_total,$earnings_incentive,$prefecture_id,$request,$status_controller) {
                OrderDemaecan::create([
                    'user_id' => $user_id,
                    'earnings_base' => $earnings_base,
                    'earnings_total' => $earnings_total,
                    'earnings_incentive' => $earnings_incentive,
                    'prefecture_id' => $prefecture_id,
                ]);
        
                $status_controller->update($request,$earnings_total);
            });
        }else{
            DB::transaction(function () use($user_id,$earnings_base,$earnings_total,$earnings_incentive,$prefecture_id,$request,$status_controller) {
                OrderDemaecan::create([
                    'user_id' => $user_id,
                    'earnings_base' => $earnings_base,
                    'earnings_total' => $earnings_total,
                    'earnings_incentive' => $earnings_incentive,
                    'prefecture_id' => $prefecture_id,
                ]);
        
                $status_controller->store($request,$earnings_total,$prefecture_id);
            });
        }

        return \response()->json([
            'message' => 'success',
        ], 201);
    }

    public function index(Request $request)
    {
        
    }
}
