<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function store(AnnouncementRequest $request)
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

    public function index(Request $request)
    {
        $announcements =  Announcement::with('reads')->get();

        foreach($announcements as $announcement){
            foreach($announcement->reads as $read){
                if($request->user()->id == $read->user_id && $read->read == 1){
                    $announcement['read'] = 1;
                }else{
                    $announcement['read'] = 0;
                }
            }
        }

        return AnnouncementResource::collection($announcements);
    }

    public function show(Request $request)
    {
        $announcement_id = $request->id;
        $announcement = Announcement::find($announcement_id);

        return new AnnouncementResource($announcement);
    }
}