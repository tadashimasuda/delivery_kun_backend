<?php

namespace App\Http\Controllers;

use App\Http\Requests\EarningsIncentivesSheetRequest;
use App\Models\EarningsIncentivesSheet;
use Illuminate\Support\Str;

class EarningsIncentivesSheetController extends Controller
{
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
}