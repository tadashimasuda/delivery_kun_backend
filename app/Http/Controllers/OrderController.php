<?php

namespace App\Http\Controllers;

use App\Models\OrderDemaecan;
use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = User::with('prefecture')->find($request->user()->id);

        $earnings_incentive = $request->earnings_incentive;
        $earnings_base = $user->prefecture->earnings_base;
        $earnings_total = $earnings_incentive * $earnings_base;

        OrderDemaecan::create([
            'user_id' => $request->user()->id,
            'earnings_base' => $earnings_base,
            'earnings_total' => $earnings_total,
            'earnings_incentive' => $request->earnings_incentive,
            'prefecture_id' => $user->prefecture_id,
        ]);

        return \response()->json([
            'message' => 'success',
            'incentive' => $earnings_incentive
        ], 201);
    }
}
