<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'images' => $this->images,
            'audio' => $this->audio,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'likesCount' => $this->likes()->count(),
            'commentsCount' => $this->comments()->count(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar,
                'hashId' => $this->user->hashId,
            ],
            'tags' => $this->tags,
            'is_liked' => $this->likes()->where('user_id', auth()->id())->exists(),
        ];
    }
} 