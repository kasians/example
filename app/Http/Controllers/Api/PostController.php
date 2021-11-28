<?php

namespace App\Http\Controllers\Api;

use App\Commands\CreatePostCommand;
use App\Commands\UpdatePostCommand;
use App\Constants\PostStatus;
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

    public function update(string $id, Request $request): PostResource
    {
        $validator = Validator::make(
            [
                'id' => $id,
                'text' => $request->json('text'),
                'name' => $request->json('name'),
                'status' => $request->json('status'),
            ],
            [
                'id' => ['required', 'uuid', 'exists:posts,id'],
                'name' => ['required', 'string', 'max:255'],
                'text' => ['required', 'string'],
                'status' => [
                    'required',
                    'string',
                    'in:' . implode(',', [
                        PostStatus::DRAFT,
                        PostStatus::ACTIVE,
                        PostStatus::BLOCKED,
                    ]),
                ],
            ]
        );
        $validator->validate();

        $command = new UpdatePostCommand(
            $id,
            $request->json('name'),
            $request->json('text'),
            $request->json('status'),
        );
        $this->commandBus->handle($command);

        /** @var Post $post */
        $post = Post::query()->find($command->getId());

        return new PostResource($post);
    }
}
