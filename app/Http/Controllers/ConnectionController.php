<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connection;

class ConnectionController extends Controller
{
    public function followUser(Request $request)
    {
        $user = auth()->user();
        $followingId = $request->input('following_id');

        $connection = Connection::create([
            'user_id' => $user->id,
            'following_id' => $followingId,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'User followed successfully']);
    }

    public function getFollowers()
    {
        $user = auth()->user();
        $followers = Connection::where('following_id', $user->id)->get();
        return response()->json($followers);
    }

    public function getFollowing()
    {
        $user = auth()->user();
        $following = Connection::where('user_id', $user->id)->get();
        return response()->json($following);
    }

    public function acceptFollowRequest(Request $request)
    {
        $user = auth()->user();
        $connectionId = $request->input('connection_id');
        $connection = Connection::find($connectionId);
        $connection->status = 'accepted';
        $connection->save();
        return response()->json(['message' => 'Follow request accepted successfully']);
    }

    public function rejectFollowRequest(Request $request)
    {
        $user = auth()->user();
        $connectionId = $request->input('connection_id');
        $connection = Connection::find($connectionId);
        $connection->status = 'rejected';
        $connection->save();
        return response()->json(['message' => 'Follow request rejected successfully']);
    }
}
