<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementRead;
use Illuminate\Http\Request;

class AnnouncementReadController extends Controller
{
    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $announcement_id = $request->id;

        AnnouncementRead::firstOrCreate([
            'user_id' => $user_id,
            'announcement_id' => $announcement_id,
            'read' => 1
        ]);
        
        return response()->json(null,201);
    }
}
