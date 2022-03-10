<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusRequest;
use App\Http\Resources\StatusResource;
use App\Models\OrderDemaecan;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        
            $chart_data = OrderDemaecan::select(DB::raw('hour(created_at) as hour'), DB::raw('COUNT(id) as count'))
            ->groupBy(DB::raw('created_at'))
            ->get();
            $status['chart_data'] = $chart_data;
            return new StatusResource($status);
        }else{
            return \response([],204);
        }
    }
}
