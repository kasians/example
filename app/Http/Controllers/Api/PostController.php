<?php

namespace App\Http\Controllers\Api;

use App\Commands\CreatePostCommand;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Interfaces\CommandBusInterface;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class PostController extends Controller
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

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

    public function create(Request $request): PostResource
    {
        $validator = Validator::make(
            [
                'text' => $request->json('text'),
                'name' => $request->json('name'),
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'text' => ['required', 'string'],
            ]
        );
        $validator->validate();

        $command = new CreatePostCommand(
            Uuid::uuid4(),
            $request->json('name'),
            $request->json('text')
        );
        $this->commandBus->handle($command);

        /** @var Post $post */
        $post = Post::query()->find($command->getId());
        $post->wasRecentlyCreated = true;

        return new PostResource($post);
    }
}
