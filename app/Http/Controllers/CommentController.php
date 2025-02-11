<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    //
    public function createComment(Request $request)
    {
        $validator = validator($request->all(), [
            'comment' => 'required|string|max:255',
            'postId' => 'required|exists:posts,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create([
            'comment' => $request->comment,
            'post_id' => $request->postId,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => $comment->load('user')
        ], 201);
    }

    public function getComments(Request $request, $postId = null)
    {
   
        if (!$postId) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => 'Post ID is required'
            ], 422);
        }

        $comments = Comment::where('post_id', $postId)->orderBy('created_at', 'desc')->get();

        return response()->json($comments->load('user'));
    }
}
