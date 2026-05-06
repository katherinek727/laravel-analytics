<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use App\Models\Joke;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Get statistics for the dashboard
        $stats = $this->getDashboardStats();
        
        // Get hourly visits data for the chart
        $hourlyVisits = $this->getHourlyVisits();
        
        // Get city distribution data for the pie chart
        $cityDistribution = $this->getCityDistribution();
        
        // Get device statistics
        $deviceStats = $this->getDeviceStats();
        
        // Get recent visits
        $recentVisits = PageVisit::with([])
            ->orderBy('visited_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get recent jokes fetched
        $recentJokes = Joke::orderBy('fetched_at', 'desc')
            ->limit(5)
            ->get();

        return view('analytics.dashboard', compact(
            'stats',
            'hourlyVisits',
            'cityDistribution',
            'deviceStats',
            'recentVisits',
            'recentJokes'
        ));
    }

    /**
     * Get dashboard statistics.
     */
    protected function getDashboardStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::today()->subWeek();

        return [
            'total_visits' => PageVisit::count(),
            'unique_visitors' => PageVisit::distinct('visitor_id')->count('visitor_id'),
            'today_visits' => PageVisit::whereDate('visited_at', $today)->count(),
            'yesterday_visits' => PageVisit::whereDate('visited_at', $yesterday)->count(),
            'week_visits' => PageVisit::where('visited_at', '>=', $lastWeek)->count(),
            'avg_duration' => round(PageVisit::whereNotNull('duration')->avg('duration') / 1000, 1), // Convert to seconds
            'total_jokes' => Joke::count(),
            'jokes_today' => Joke::whereDate('fetched_at', $today)->count(),
        ];
    }

    /**
     * Get hourly visits data for the chart.
     */
    protected function getHourlyVisits(): array
    {
        $last24Hours = Carbon::now()->subHours(24);
        
        $hourlyData = PageVisit::select(
            DB::raw('HOUR(visited_at) as hour'),
            DB::raw('COUNT(DISTINCT visitor_id) as unique_visits'),
            DB::raw('COUNT(*) as total_visits')
        )
        ->where('visited_at', '>=', $last24Hours)
        ->groupBy(DB::raw('HOUR(visited_at)'))
        ->orderBy('hour')
        ->get();

        // Format for Chart.js
        $hours = [];
        $uniqueVisits = [];
        $totalVisits = [];

        for ($i = 0; $i < 24; $i++) {
            $hourData = $hourlyData->firstWhere('hour', $i);
            $hours[] = sprintf('%02d:00', $i);
            $uniqueVisits[] = $hourData ? $hourData->unique_visits : 0;
            $totalVisits[] = $hourData ? $hourData->total_visits : 0;
        }

        return [
            'hours' => $hours,
            'unique_visits' => $uniqueVisits,
            'total_visits' => $totalVisits,
        ];
    }

    /**
     * Get city distribution data for the pie chart.
     */
    protected function getCityDistribution(): array
    {
        $cityData = PageVisit::select(
            'city',
            DB::raw('COUNT(*) as visits'),
            DB::raw('COUNT(DISTINCT visitor_id) as unique_visitors')
        )
        ->whereNotNull('city')
        ->where('city', '!=', 'Local Network')
        ->groupBy('city')
        ->orderByDesc('visits')
        ->limit(10)
        ->get();

        $cities = [];
        $visits = [];
        $colors = $this->generateChartColors(count($cityData));

        foreach ($cityData as $index => $data) {
            $cities[] = $data->city ?: 'Unknown';
            $visits[] = $data->visits;
        }

        return [
            'cities' => $cities,
            'visits' => $visits,
            'colors' => $colors,
        ];
    }

    /**
     * Get device statistics.
     */
    protected function getDeviceStats(): array
    {
        $deviceData = PageVisit::select(
            'device_type',
            DB::raw('COUNT(*) as count'),
            DB::raw('ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM page_visits), 1) as percentage')
        )
        ->whereNotNull('device_type')
        ->groupBy('device_type')
        ->orderByDesc('count')
        ->get();

        $devices = [];
        $counts = [];
        $percentages = [];
        $colors = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];

        foreach ($deviceData as $index => $data) {
            $devices[] = ucfirst($data->device_type);
            $counts[] = $data->count;
            $percentages[] = $data->percentage;
        }

        return [
            'devices' => $devices,
            'counts' => $counts,
            'percentages' => $percentages,
            'colors' => array_slice($colors, 0, count($devices)),
        ];
    }

    /**
     * Generate chart colors.
     */
    protected function generateChartColors(int $count): array
    {
        $baseColors = [
            '#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
        ];

        if ($count <= count($baseColors)) {
            return array_slice($baseColors, 0, $count);
        }

        // Generate additional colors if needed
        $colors = $baseColors;
        for ($i = count($baseColors); $i < $count; $i++) {
            $hue = ($i * 137.508) % 360; // Golden angle approximation
            $colors[] = "hsl($hue, 70%, 60%)";
        }

        return $colors;
    }

    /**
     * API endpoint for hourly visits data (for AJAX updates).
     */
    public function hourlyVisitsData()
    {
        $data = $this->getHourlyVisits();
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'updated_at' => now()->toISOString(),
        ]);
    }

    /**
     * API endpoint for city distribution data.
     */
    public function cityDistributionData()
    {
        $data = $this->getCityDistribution();
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'updated_at' => now()->toISOString(),
        ]);
    }

    /**
     * API endpoint for dashboard statistics.
     */
    public function dashboardStats()
    {
        $stats = $this->getDashboardStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'updated_at' => now()->toISOString(),
        ]);
    }
}