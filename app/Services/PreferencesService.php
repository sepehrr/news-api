<?php

namespace App\Services;

use App\Http\Requests\UpdatePreferencesRequest;
use App\Http\Resources\PreferenceResource;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use App\Services\Interfaces\PreferencesServiceInterface;

class PreferencesService implements PreferencesServiceInterface
{
    public const PREFERABLE_TYPES = [
        'categories' => Category::class,
        'authors' => Author::class,
        'sources' => Source::class,
    ];

    /**
     * Get the preferences for a user
     *
     * @return array<string, array<PreferenceResource>>
     */
    public function getPreferences(User $user = null): array
    {
        $user = $user ?? auth()->user();
        $preferences = $user->preferences()->with('preferable')->get();

        return [
            'categories' => PreferenceResource::collection($preferences->where('preferable_type', Category::class)),
            'authors' => PreferenceResource::collection($preferences->where('preferable_type', Author::class)),
            'sources' => PreferenceResource::collection($preferences->where('preferable_type', Source::class)),
        ];
    }

    /**
     * Update the preferences for a user
     *
     * @return array<string, array<PreferenceResource>>
     */
    public function updatePreferences(UpdatePreferencesRequest $request): array
    {
        $user = $request->user();
        $user->preferences()->delete();

        $preferences = [];

        foreach (self::PREFERABLE_TYPES as $preferableType => $preferableClass) {
            $preferences = array_merge(
                $preferences,
                $this->createPreferencesForType($user, $request->{$preferableType} ?? [], $preferableClass)
            );
        }

        $user->preferences()->saveMany($preferences);

        return $this->getPreferences($user);
    }

    /**
     * Create preferences for a type
     */
    private function createPreferencesForType(User $user, array $preferableIds, string $preferableClass): array
    {
        $preferences = [];

        foreach ($preferableIds as $preferableId) {
            $preferences[] = $user->preferences()->make([
                'preferable_id' => $preferableId,
                'preferable_type' => $preferableClass,
            ]);
        }

        return $preferences;
    }
}
