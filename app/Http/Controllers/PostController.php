<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\ProfileResource;

class PostController extends Controller
{
    /**
     * Create a new post
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPost(Request $request)
    {
        try {

            if($request->content === null && $request->images === null && $request->audio === null){
                return response()->json([
                    'message' => 'Content is required'
                ], 422);
            }
            // Create and save the new post
            $post = new Post();
            $post->content = $request->content;
            $post->user_id = $request->user()->id;
            $post->save();

            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('posts', 'public');
                    $images[] = $path;
                }

                $post->images = $images;
            }

            if ($request->hasFile('audio')) {
                $audio = $request->file('audio')->store('posts', 'public');
                $post->audio = $audio;
            }
            if ($request->has('tags')) {
                $post->tags = $request->tags;
            }

            $post->save();

            return response()->json([
                'message' => 'Post created successfully',
                'post' => new PostResource($post)
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchNewsFeed(Request $request)
    {
        $query = $request->input('query');
        $posts = Post::where('content', 'like', '%' . $query . '%')->get();
        $profiles = $this->searchProfiles($query);
        return response()->json([
            'posts' => PostResource::collection($posts),
            'profiles' => $profiles
        ]);
    }

    public function getOgImage(Request $request)
    {
        $url = $request->input('url');
        //request to url
        $response = Http::get($url);
        $html = $response->body();
        $ogImage = $this->getOgImageFromUrl($html);
        return response()->json(['ogImage' => $ogImage]);
    }

    private function getOgImageFromUrl($html)
    {
        // Use DOMDocument to parse HTML
        $doc = new \DOMDocument();
        @$doc->loadHTML($html);

        // Look for og:image meta tag
        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('property') === 'og:image') {
                return $meta->getAttribute('content');
            }
        }

        // Fallback: Look for first image
        $images = $doc->getElementsByTagName('img');
        if ($images->length > 0) {
            return $images->item(0)->getAttribute('src');
        }

        return null;
    }

    
    /** 
     * Get posts by hash id
     * 
     * @param Request $request
     * @param string $hashId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostsByHashId(Request $request, $hashId)
    {
        $user = User::where('hashId', $hashId)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return PostResource::collection($posts);
    }

    /**
     * Get posts for authenticated user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPosts()
    {
        try {
            $posts = Post::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            return PostResource::collection($posts);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchProfiles($query)
    {
        $query = $query;
        $profiles = User::where('name', 'like', '%' . $query . '%')->get();
        return ProfileResource::collection($profiles);
    }

    /**
     * Get paginated news feed for authenticated user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewsFeed(Request $request)
    {
        try {
            // Get pagination parameters
            $page = $request->input('page', 1);
            $q = $request->input('q');

            $profiles = $this->searchProfiles($q);

            $perPage = 10;

            $user = auth()->user();
            $following = $user->following()->pluck('users.id')->toArray();

            // Get posts from followed users and own posts
            $posts = Post::with(['user', 'comments', 'likes'])
                ->where('content', 'like', '%' . $q . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'posts' => PostResource::collection($posts->items()),
                'profiles' => $profiles,
                'hasMore' => $posts->hasMorePages()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch news feed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Like a post
     * 
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function likePost(Request $request, $postId)
    {
        try {
            $user = auth()->user();
            $post = Post::findOrFail($postId);

            // Attach user like to post
            if ($post->likes()->where('user_id', $user->id)->exists()) {
                $post->likes()->detach($user->id);
            } else {
                $post->likes()->attach($user->id);
            }

            return response()->json([
                'message' => 'Post liked successfully'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to like post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get likes for a post
     * 
     * @param Request $request
     * @param int $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLikes(Request $request, $postId)
    {
        try {
            $post = Post::findOrFail($postId);
            $likes = $post->likes()->select('post_likes.id', 'users.name', 'users.avatar')->get();

            return response()->json([
                'likes' => $likes
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch likes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}