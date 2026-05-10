<?php

namespace App\Http\Controllers;

use App\Http\Requests\Favorites\ToggleFavoriteRequest;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function __construct(private FavoriteService $favorites) {}

    public function index(Request $request): View
    {
        return view('pages.favorites', [
            'favorites' => $this->favorites->summary($request),
        ]);
    }

    public function summary(Request $request): JsonResponse
    {
        return response()->json($this->jsonSummary($request));
    }

    public function store(ToggleFavoriteRequest $request): JsonResponse
    {
        $result = $this->favorites->toggle($request, $request->validated()['slug']);

        return response()->json([
            ...$this->jsonSummary($request),
            'favorited' => $result['favorited'],
        ]);
    }

    public function destroy(Request $request, string $slug): JsonResponse
    {
        $this->favorites->remove($request, $slug);

        return response()->json([
            ...$this->jsonSummary($request),
            'favorited' => false,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function jsonSummary(Request $request): array
    {
        $summary = $this->favorites->summary($request);

        return [
            'count' => $summary['count'],
            'slugs' => $summary['slugs'],
            'items' => $summary['items']->values(),
        ];
    }
}
