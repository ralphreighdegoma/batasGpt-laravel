<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JurisprudenceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Http\Controllers\CommentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/search', [JurisprudenceController::class, 'search']);

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/post', [PostController::class, 'createPost']);
    Route::post('/upload-avatar', [AuthController::class, 'uploadAvatar']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::get('/posts', [PostController::class, 'getPosts']);

    //api for news feed
    Route::get('/news-feed', [PostController::class, 'getNewsFeed']);

    //api for search users
    Route::get('/users/search', [UserController::class, 'search']);

    //api for connections
    Route::get('/users/connections', [UserController::class, 'getConnections']);



    Route::post('/follow', [ConnectionController::class, 'followUser']);
    Route::get('/followers', [ConnectionController::class, 'getFollowers']);
    Route::get('/following', [ConnectionController::class, 'getFollowing']);
    Route::post('/accept-follow-request', [ConnectionController::class, 'acceptFollowRequest']);
    Route::post('/reject-follow-request', [ConnectionController::class, 'rejectFollowRequest']);


    //coments
    Route::post('/comments', [CommentController::class, 'createComment']);
    Route::get('/comments/{postId}', [CommentController::class, 'getComments']);
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});


Route::get('/email/code-verify/{code}', function ($code) {
$user = User::where('verification_code', $code)->first();
    if (!$user) {
        return redirect(env('FRONTEND_URL') . '/verify?error=invalid_code');
    }
    $user->email_verified_at = now();
    $user->save();
    return response()->json(['message' => 'Email verified successfully']);
});
