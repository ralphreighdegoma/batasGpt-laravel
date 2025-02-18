<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
class TagsController extends Controller
{
    //
    public function index()
    {
        $tags = Tag::orderBy('created_at', 'desc')->get();
        return response()->json($tags);
    }
    //search tags
    public function search(Request $request)
    {
        $tags = Tag::where('name', 'like', '%' . $request->q . '%')->limit(2)->get();
        return response()->json($tags);
    }
}
