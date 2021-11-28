<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        /** @var Post $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'status' => $this->status,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
