<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $user_id = $request->user()->id;

        $user = User::find($user_id);

        if($user->is_super_user == 0){
            return response()->json(['message' => 'you are not superuser'],403);
        }

        Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json(null,201);
    }
}
