<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Http\Requests\UpdateActualCostRequest;
use App\Http\Resources\StatusResource;
use App\Models\OrderDemaecan;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function date_format($date)
    {
        $date_split_y = substr($date, 0, 4);
        $date_split_m = substr($date, 4, 2);
        $date_split_d = substr($date, 6, 2);

        $format_day = $date_split_y . '-' . $date_split_m . '-' . $date_split_d;

        return $format_day;
    }

    public function update($request, $earnings_total)
    {
        $today = Carbon::today();
        Status::where('user_id', $request->user()->id)->whereDate('created_at', $today)->update([
            'days_earnings_total' => DB::raw("days_earnings_total + '$earnings_total'"),
            'days_earnings_qty' => DB::raw("days_earnings_qty + 1"),
        ]);
    }

    public function is_status($user_id)
    {
        $today = Carbon::today();

        return DB::table('statuses')->where('user_id', $user_id)->whereDate('created_at', $today)->exists();
    }

    public function store($request, $earnings_total, $prefecture_id)
    {
        Status::create([
            'user_id' => $request->user()->id,
            'days_earnings_total' => $earnings_total,
            'days_earnings_qty' => 1,
            'prefecture_id' => $prefecture_id
        ]);
    }

    public function index(StatusRequest $request)
    {
        $date = $request->query('date');
        $user_id = $request->query('user_id');

        $date_format = $this->date_format($date);
        
        if(Status::with('user')->where('user_id', $user_id)->whereDate('created_at', '=', $date_format)->exists()){
            $status = Status::with('user')->where('user_id', $user_id)->whereDate('created_at', '=', $date_format)->first();
        
            $chart_data = OrderDemaecan::select(DB::raw('hour(order_received_at) as hour'), DB::raw('COUNT(id) as count'))->where('user_id', $user_id)->whereDate('created_at', '=', $date_format)->groupby('hour')->get();

            $order_controller = app()->make('App\Http\Controllers\OrderController');

            $first_time = $order_controller->getDateFirstOrder($date,$user_id);
            $last_time = $order_controller->getDateLastOrder($date,$user_id);
            
            if($this->isToday($first_time)){
                //最初の受注の時間〜現在の時間
                $start_time = new Carbon($first_time);
                $currnet_time = Carbon::now();
                
                $diff_time = $start_time->diff($currnet_time);
                $online_time = $diff_time->format("%h時間%i分");
            }else{
                //最初の受注の時間〜最後の受注の時間
                $online_time = $this->deffOnlineTime($first_time, $last_time);
            }

            $status['chart_data'] = $chart_data;
            $status['online_time'] = $online_time;
            $status['start_time'] = new Carbon($first_time);
            $status['end_time'] = new Carbon($last_time);
 
            return new StatusResource($status);
        }else{
            return \response([],204);
        }
    }

    public function deffOnlineTime($start_time, $finish_time)
    {
        $start_time = new Carbon($start_time);
        $finish_time = new Carbon($finish_time);
        $diff_time = $start_time->diff($finish_time);
        return $diff_time->format("%h時間%i分");
    }

    public function isToday($date)
    {
        $created_at = new Carbon($date);

        if($created_at->isToday()){
            return true;
        }else{
            return false;
        }
    }

    public function updateActualCost(UpdateActualCostRequest $request)
    {
        $user_id = $request->user()->id;
        $date = $this->date_format($request->query('date'));

        $user_status = Status::where('user_id',$user_id)->whereDate('created_at', '=', $date)->first();

        $this->authorize('update', $user_status);

        $user_status->update([
            'actual_cost' => $request->actual_cost
        ]);

        return \response()->json([
            'message' => 'success'
        ],201);
    }

    public function recountTotal($user_id,$created_at,$earnings_total)
    {
        $user_status = Status::where('user_id',$user_id)->whereDate('created_at', '=', $created_at)->first();
        $user_status->update([
            'days_earnings_total' => $earnings_total
        ]);
    }

    public function decrementOrderQty($user_id,$created_at)
    {
        $user_status = Status::where('user_id',$user_id)->whereDate('created_at', '=', $created_at)->first();
        $user_status->update([
            'days_earnings_qty' => DB::raw("days_earnings_qty - 1"),
        ]);
    }
}
