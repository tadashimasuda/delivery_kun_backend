<?php

namespace App\Http\Controllers;

use App\Http\Requests\DaysEarningsIncentiveRequest;
use App\Http\Resources\DaysEarningsIncentiveResource;
use App\Models\DaysEarningsIncentive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\OAuth1\Client\Server\Trello;

class DaysEarningsIncentiveController extends Controller
{
    public function arrange_list($data,$user_id,$is_timestamp)
    {
        $new_keys = ['user_id','incentive_hour','earnings_incentive'];
        $new_keys_timestamps = ['user_id','incentive_hour','earnings_incentive','created_at','updated_at'];
        $timestamp = Carbon::now();
        $insert_data = [];

        foreach($data as $row){
            $row['hour'] = Carbon::createFromTime($row['hour'],0,0,);
            $values = array_values($row);
            array_unshift($values,$user_id);

            if($is_timestamp){
                $values[] = $timestamp;
                $values[] = $timestamp;

                $new_row = array_combine($new_keys_timestamps,$values);
            }else{
                $new_row = array_combine($new_keys,$values);
            }

            $insert_data[] = $new_row;
        }

        return $insert_data;
    }

    public function isTodayIncentive($user_id)
    {
        $today = Carbon::now();
        $current_hour = Carbon::createFromTime($today->hour,0,0);

        $is_today_incentive = DaysEarningsIncentive::where('user_id',$user_id)->where('incentive_hour',$current_hour)->exists();

        return $is_today_incentive;
    }

    public function getTodayIncentive($user_id)
    {
        $today = Carbon::now();
        $current_hour = Carbon::createFromTime($today->hour,0,0);

        $today_incentive = DaysEarningsIncentive::where('user_id',$user_id)->where('incentive_hour',$current_hour)->first();
        $earnings_incentive = $today_incentive->earnings_incentive;
        
        return $earnings_incentive;
    }

    public function store(DaysEarningsIncentiveRequest $request)
    {
        $user_id = $request->user()->id;
        $today = Carbon::today();

        $today_incentives_count = DaysEarningsIncentive::where('user_id',$user_id)->whereDate('created_at', $today)->count();

        if($today_incentives_count > 0){
            $insert_data = $this->arrange_list($request['data'],$user_id,false);
            
            foreach ($insert_data as $row) {
                DaysEarningsIncentive::where('user_id',$user_id)
                ->where('incentive_hour', $row['incentive_hour'])
                ->whereDate('created_at', $today)->update(
                    [
                        "earnings_incentive" => $row['earnings_incentive'],
                    ]
                );
            }
            
            return response()->json([],204);
        }else{
            $insert_data = $this->arrange_list($request['data'],$user_id,true);

            DaysEarningsIncentive::insert($insert_data);

            return response()->json([],204);
        }
    }

    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $today = Carbon::today();

        $today_incentives_count = DaysEarningsIncentive::where('user_id',$user_id)->whereDate('created_at', $today)->count();

        if($today_incentives_count == 18){
            $today_incentives = DaysEarningsIncentive::where('user_id',$user_id)->whereDate('created_at', $today)->orderBy('incentive_hour')->get();

            return DaysEarningsIncentiveResource::collection($today_incentives);
        }else{
            return response()->json(['message'=>'nodata'],200);
        }
    }
}