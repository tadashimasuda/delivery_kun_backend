<?php

namespace App\Http\Controllers;

use App\Http\Requests\EarningsIncentivesSheetRequest;
use App\Http\Requests\UpdateEarningsIncentivesSheetRequest;
use App\Http\Resources\EarningsIncentiveSheetResource;
use App\Models\EarningsIncentivesSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EarningsIncentivesSheetController extends Controller
{
    public function index(Request $request)
    {
        $incentive_sheets = EarningsIncentivesSheet::where('user_id',$request->user()->id)->get();

        return EarningsIncentiveSheetResource::collection($incentive_sheets);
    }
    public function store(EarningsIncentivesSheetRequest $request)
    {
        EarningsIncentivesSheet::create([
            'id' => Str::uuid(),
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'earnings_incentives' => $request->earnings_incentives
        ]);

        return response()->json(null,201);
    }

    public function update(UpdateEarningsIncentivesSheetRequest $request)
    {
        $sheet = EarningsIncentivesSheet::where('id',$request->id)->first();

        if(!$sheet){
            return response()->json(null,404);
        }

        $this->authorize('update',$sheet);

        EarningsIncentivesSheet::where('id',$request->id)->update([
            'title' => $request->title,
            'earnings_incentives' => $request->earnings_incentives
        ]);

        return response()->json(null,201);
    }

    public function destroy(Request $request)
    {
        $sheet = EarningsIncentivesSheet::where('id',$request->id)->first();
        
        if(!$sheet){
            return response()->json(null,404);
        }

        $this->authorize('delete',$sheet);

        EarningsIncentivesSheet::where('id',$request->id)->delete();

        return response()->json(null,204);
    }
}