<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CitationController extends Controller
{
    public function fetch(Request $request)
    {
        $data = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $response = Http::timeout(8)->post(
            config('services.citation_fetcher.url'),
            ['url' => $data['url']]
        );

        if (!$response->successful()) {
            return response()->json([
                'ok' => false,
                'error' => 'Citation fetch failed',
                'details' => $response->json(),
            ], 502);
        }

        return response()->json($response->json());
    }
}