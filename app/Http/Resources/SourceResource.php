<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Source",
 *     title="Source",
 *     description="Source model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="TechCrunch"),
 *     @OA\Property(property="url", type="string", format="uri", example="https://techcrunch.com"),
 *     @OA\Property(property="description", type="string", example="Technology news and analysis")
 * )
 */
class SourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'description' => $this->description,
        ];
    }
}
