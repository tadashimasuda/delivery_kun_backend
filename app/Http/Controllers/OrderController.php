<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\OrderDemaecan;
use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function date_format($date)
    {
        $date_split_y = substr($date,0,4);
        $date_split_m = substr($date,4,2);
        $date_split_d = substr($date,6,2);

        $format_day = $date_split_y .'-'. $date_split_m .'-'.$date_split_d;

        return $format_day;
    }
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
        $date = $request->query('date');

        if (!$date) {
            return \response()->json([
                'message' => 'InvalidQueryParameterValue'
            ],400);
        }

        $date_format = $this->date_format($date);
        
        $user_id = $request->user()->id;
        $orders = OrderDemaecan::where('user_id',$user_id)->whereDate('created_at', '=', $date_format)->get();

        return OrderResource::collection($orders);
    }

    
}