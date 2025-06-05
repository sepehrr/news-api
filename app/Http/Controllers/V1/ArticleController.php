<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\ArticleListFilter;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Articles",
 *     description="API Endpoints for managing articles"
 * )
 */
class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/articles",
     *     summary="Get list of articles",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword for title and body",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter articles published after this date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter articles published before this date",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Filter by author ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter by source ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Article")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request, ArticleListFilter $filter)
    {
        $articles = $filter->apply()
            ->with(['category', 'author', 'source'])
            ->orderBy('published_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return ArticleResource::collection($articles);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles/{article}",
     *     summary="Get article details",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */
    public function show(Article $article)
    {
        $article->load(['category', 'author', 'source']);

        return new ArticleResource($article);
    }
}
