<?php

use App\Models\Joke;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// API route to return all jokes as JSON
Route::get('/api/jokes', function () {
    try {
        $jokes = Joke::orderBy('fetched_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'count' => $jokes->count(),
            'data' => $jokes,
            'timestamp' => now()->toISOString(),
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch jokes',
            'message' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
        ], 500);
    }
});

// API route to return a single random joke
Route::get('/api/jokes/random', function () {
    try {
        $joke = Joke::inRandomOrder()->first();
        
        if (!$joke) {
            return response()->json([
                'success' => false,
                'error' => 'No jokes available in database',
                'timestamp' => now()->toISOString(),
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $joke,
            'timestamp' => now()->toISOString(),
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch random joke',
            'message' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
        ], 500);
    }
});
