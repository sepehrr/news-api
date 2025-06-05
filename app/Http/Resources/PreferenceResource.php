<?php

namespace App\Http\Resources;

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Preference",
 *     title="Preference",
 *     description="User preference model",
 *     @OA\Property(property="type", type="string", example="category", enum={"category", "author", "source"}),
 *     @OA\Property(
 *         property="preferable",
 *         oneOf={
 *             @OA\Schema(ref="#/components/schemas/Category"),
 *             @OA\Schema(ref="#/components/schemas/Author"),
 *             @OA\Schema(ref="#/components/schemas/Source")
 *         }
 *     )
 * )
 */
class PreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->getType($this->preferable_type),
            'preferable' => $this->getPreferableResource($this->preferable_type),
        ];
    }

    private function getType(string $preferableType)
    {
        return match ($preferableType) {
            Category::class => 'category',
            Author::class => 'author',
            Source::class => 'source',
        };
    }

    private function getPreferableResource(string $preferableType)
    {
        return match ($preferableType) {
            Category::class => new CategoryResource($this->preferable),
            Author::class => new AuthorResource($this->preferable),
            Source::class => new SourceResource($this->preferable),
        };
    }
}
