<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function getList(): ResourceCollection
    {
        return PostResource::collection(Post::all());
    }

    public function get(string $id): PostResource
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'uuid', 'exists:posts,id']]
        );
        $validator->validate();

        return new PostResource(Post::query()->find($id));
    }
}
