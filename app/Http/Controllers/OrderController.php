<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\OrderDemaecan;
use App\Models\Prefecture;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function date_format($date)
    {
        $date_split_y = substr($date, 0, 4);
        $date_split_m = substr($date, 4, 2);
        $date_split_d = substr($date, 6, 2);

        $format_day = $date_split_y . '-' . $date_split_m . '-' . $date_split_d;

        return $format_day;
    }

    public function store(OrderRequest $request)
    {
        $user = User::with('prefecture')->find($request->user()->id);

        $status_controller = app()->make('App\Http\Controllers\StatusController');
        $incentive_controller = app()->make('App\Http\Controllers\EarningsIncentivesSheetController');
        $earnings_base_controller = app()->make('App\Http\Controllers\EarningsBaseController');

        $earnings_incentive = $request->earnings_incentive;
        $earnings_base = $user->prefecture->earnings_base;
        $sheet_id = $request->sheetId;
        $user_id = $request->user()->id;
        $prefecture_id = $user->prefecture_id;
        $current_incentive = $incentive_controller->get_current_incentive($sheet_id);
        $is_earnings_base = $earnings_base_controller->is_earningsBase($user->id);

        $distance_type = $request->distance_type;
        $distance_pattern1 = [0, 60, 150, 270]; //tokyo,saitama,chiba,kanagawa
        $distance_pattern2 = [0, 50, 120, 220]; //okinawa
        $distance_pattern3 = [0, 50, 120, 220]; //other

        if ($current_incentive) {
            $earnings_incentive = $current_incentive;
        }

        if ($is_earnings_base) {
            $earnings_base = $earnings_base_controller->get_earningsBase($user->id);
        }

        if ($prefecture_id == 11 || 12 || 13 || 14) {
            $earnings_distance_base = $distance_pattern1[$distance_type];
        } else if ($prefecture_id == 47) {
            $earnings_distance_base = $distance_pattern2[$distance_type];
        } else {
            $earnings_distance_base = $distance_pattern3[$distance_type];
        }

        DB::transaction(function () use ($user_id, $earnings_base, $earnings_distance_base, $earnings_incentive, $prefecture_id, $request, $status_controller) {
            $earnings_total = $earnings_incentive * ($earnings_base + $earnings_distance_base);

            OrderDemaecan::create([
                'user_id' => $user_id,
                'earnings_base' => $earnings_base,
                'earnings_distance_base' => $earnings_distance_base,
                'earnings_total' => $earnings_total,
                'earnings_incentive' => $earnings_incentive,
                'prefecture_id' => $prefecture_id,
            ]);

            if ($status_controller->is_status($user_id)) {
                $status_controller->update($request, $earnings_total);
            } else {
                $status_controller->store($request, $earnings_total, $prefecture_id);
            }
        });

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
            ], 400);
        }

        $date_format = $this->date_format($date);

        $user_id = $request->user()->id;
        $orders = OrderDemaecan::where('user_id', $user_id)->whereDate('created_at', '=', $date_format)->orderBy('order_received_at', 'asc')->get();

        return OrderResource::collection($orders);
    }

    public function show(Request $request)
    {
        $order_id = $request->id;
        $order = OrderDemaecan::find($order_id);

        return new OrderResource($order);
    }

    public function update(OrderUpdateRequest $request)
    {
        $user_id = $request->user()->id;
        $order_id = $request->id;
        $earnings_base = $request->earnings_base;
        $earnings_incentive = $request->earnings_incentive;
        $earnings_total = $earnings_base * $earnings_incentive;
        $update_created_at = new Carbon($request->update_date_time);

        $order = OrderDemaecan::find($order_id);

        if (!$order) {
            return \response()->json([
                'message' => 'データが存在しません'
            ], 404);
        }

        $this->authorize('update', $order);

        $created_at = $order->created_at;
        $status_controller = app()->make('App\Http\Controllers\StatusController');

        DB::transaction(function () use ($order_id, $created_at, $earnings_incentive, $earnings_base, $earnings_total, $update_created_at, $user_id, $status_controller) {

            OrderDemaecan::find($order_id)->update([
                'earnings_base' => $earnings_base,
                'earnings_incentive' => $earnings_incentive,
                'earnings_total' => $earnings_total,
                'order_received_at' => $update_created_at
            ]);

            $days_earnings_total = OrderDemaecan::where('user_id', $user_id)->whereDate('created_at', '=', $created_at)->sum('earnings_total');

            $status_controller->recountTotal($user_id, $created_at, $days_earnings_total);
        });

        return \response(null, 204);
    }

    public function destroy(Request $request)
    {
        $user_id = $request->user()->id;
        $order_id = $request->id;
        $order = OrderDemaecan::find($order_id);

        if (!$order) {
            return \response()->json([
                'message' => 'データが存在しません'
            ], 404);
        }

        $created_at = $order->created_at;
        $status_controller = app()->make('App\Http\Controllers\StatusController');

        $this->authorize('delete', $order);

        DB::transaction(function () use ($order_id, $user_id, $status_controller, $created_at) {
            $order = OrderDemaecan::find($order_id);
            $order->delete();

            $days_earnings_total = OrderDemaecan::where('user_id', $user_id)->whereDate('created_at', '=', $created_at)->sum('earnings_total');
            $status_controller->recountTotal($user_id, $created_at, $days_earnings_total);
            $status_controller->decrementOrderQty($user_id, $created_at);
        });

        return \response(null, 204);
    }

    public function getDateFirstOrder($date, $user_id)
    {
        $first_order = OrderDemaecan::where('user_id', $user_id)->whereDate('created_at', '=', $date)->orderBy('order_received_at', 'asc')->first();

        return $first_order->order_received_at;
    }

    public function getDateLastOrder($date, $user_id)
    {
        $last_order = OrderDemaecan::where('user_id', $user_id)->whereDate('created_at', '=', $date)->orderBy('order_received_at', 'desc')->first();

        return $last_order->order_received_at;
    }
}
