<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePreferencesRequest;
use App\Services\Interfaces\PreferencesServiceInterface;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Preferences",
 *     description="API Endpoints for managing user preferences"
 * )
 */
class PreferenceController extends Controller
{
    public function __construct(
        protected PreferencesServiceInterface $preferencesService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/preferences",
     *     summary="Get user preferences",
     *     tags={"Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category")),
     *             @OA\Property(property="authors", type="array", @OA\Items(ref="#/components/schemas/Author")),
     *             @OA\Property(property="sources", type="array", @OA\Items(ref="#/components/schemas/Source"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->success(
            data:$this->preferencesService->getPreferences()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/preferences",
     *     summary="Update user preferences",
     *     tags={"Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="categories", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category")),
     *             @OA\Property(property="authors", type="array", @OA\Items(ref="#/components/schemas/Author")),
     *             @OA\Property(property="sources", type="array", @OA\Items(ref="#/components/schemas/Source"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(UpdatePreferencesRequest $request)
    {
        return $this->success(
            data: $this->preferencesService->updatePreferences($request)
        );

    }
}
