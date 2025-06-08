<?php

namespace App\Services\Interfaces;

use App\Http\Requests\UpdatePreferencesRequest;
use App\Models\User;

interface PreferencesServiceInterface
{
    /**
     * Get the preferences for a user
     *
     * @return array<string, array<PreferenceResource>>
     */
    public function getPreferences(User $user = null): array;

    /**
     * Update the preferences for a user
     *
     * @return array<string, array<PreferenceResource>>
     */
    public function updatePreferences(UpdatePreferencesRequest $request): array;
}
