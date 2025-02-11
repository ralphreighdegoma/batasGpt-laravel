<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function createPost(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = new Post();
        $post->content = $request->content;
        $post->user_id = $request->user()->id;
        $post->save();


        return response()->json(['message' => 'Post created successfully']);
    }

    public function getPosts()
    {
        $posts = Post::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return response()->json($posts);
    }

    public function getNewsFeed(Request $request)
    {
        //this should the parameter passed "page"
        $page = $request->input('page', 1);
        $perPage = 10;

        $user = auth()->user();
        $following = $user->following()->pluck('users.id')->toArray();
        
        $posts = Post::whereIn('posts.user_id', $following)
            ->orderBy('posts.created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'posts' => $posts->items(),
            'hasMore' => $posts->hasMorePages()
        ]);
    }


} 