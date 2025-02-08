<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function createPost(Request $request)
    {
        // TODO: Implement post creation logic here
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = $request->user()->id;
        $post->save();

        return response()->json(['message' => 'Post created successfully']);
    }
} 