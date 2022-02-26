<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function update($request,$earnings_total)
    {
        //データがある時
        $today = Carbon::today();
        Status::where('user_id', $request->user()->id)->whereDate('created_at', $today)->update([
            'days_earnings_total' => DB::raw("days_earnings_total + '$earnings_total'"),
            'days_earnings_qty' => DB::raw("days_earnings_qty + 1"),
        ]);
    }
}