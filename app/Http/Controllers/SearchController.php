<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemoveRecentSearchRequest;
use App\Http\Requests\SearchSuggestionsRequest;
use App\Services\SearchAssistant;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function __invoke(SearchSuggestionsRequest $request, SearchAssistant $searchAssistant): JsonResponse
    {
        $query = $request->validated()['q'] ?? null;

        return response()->json([
            'recent' => $query === null ? $searchAssistant->recent($request) : [],
            'suggestions' => $searchAssistant->suggestions($query),
        ]);
    }

    public function destroyRecent(RemoveRecentSearchRequest $request, SearchAssistant $searchAssistant): JsonResponse
    {
        $searchAssistant->forget($request, $request->validated('term'));

        return response()->json([
            'recent' => $searchAssistant->recent($request),
        ]);
    }
}
