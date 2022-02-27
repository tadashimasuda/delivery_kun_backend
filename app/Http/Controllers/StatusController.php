<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
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

    public function index(Request $request)
    {
        return response()->json([
            'message' => 'success'
        ]);
    }
}
