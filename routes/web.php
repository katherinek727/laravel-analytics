<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Analytics Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.dashboard');
    Route::get('/analytics/hourly-data', [AnalyticsController::class, 'hourlyVisitsData'])->name('analytics.hourly');
    Route::get('/analytics/city-data', [AnalyticsController::class, 'cityDistributionData'])->name('analytics.cities');
    Route::get('/analytics/stats-data', [AnalyticsController::class, 'dashboardStats'])->name('analytics.stats');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API routes (public)
Route::get('/api/jokes', function () {
    try {
        $jokes = App\Models\Joke::orderBy('fetched_at', 'desc')->get();
        
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

Route::get('/api/jokes/random', function () {
    try {
        $joke = App\Models\Joke::inRandomOrder()->first();
        
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

// Page visit tracking API
Route::post('/api/track-visit', [App\Http\Controllers\TrackingController::class, 'track'])
    ->name('track.visit');

require __DIR__.'/auth.php';
