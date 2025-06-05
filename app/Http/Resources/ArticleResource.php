<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Article",
 *     title="Article",
 *     description="Article model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Sample Article Title"),
 *     @OA\Property(property="body", type="string", example="This is the article content..."),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2024-03-21T12:00:00+00:00"),
 *     @OA\Property(property="source", type="string", example="TechCrunch"),
 *     @OA\Property(property="category", ref="#/components/schemas/Category"),
 *     @OA\Property(property="author", ref="#/components/schemas/Author")
 * )
 */
class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'published_at' => $this->published_at->format('c'),
            'source' => $this->source,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new AuthorResource($this->whenLoaded('author')),
        ];
    }
}
