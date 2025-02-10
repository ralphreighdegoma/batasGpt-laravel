<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //search users
    public function search(Request $request)
    {
        $query = $request->input('q');
        $users = User::where('name', 'like', '%' . $query . '%')->whereNotNull('email_verified_at')
            ->where('id', '!=', $request->user()->id)
            ->get();
        return response()->json($users);
    }

    //get connections
    public function getConnections(Request $request)
    {
        $user = $request->user();
        $connections = $user->connections;
        return response()->json($connections);
    }

}
