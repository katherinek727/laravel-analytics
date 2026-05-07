<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Analytics Dashboard - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                                📊 Analytics Dashboard
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Home') }}
                            </x-nav-link>
                            <x-nav-link :href="route('analytics.dashboard')" :active="request()->routeIs('analytics.dashboard')">
                                {{ __('Analytics') }}
                            </x-nav-link>
                            <x-nav-link href="/api/jokes" target="_blank">
                                {{ __('Jokes API') }}
                            </x-nav-link>
                            <x-nav-link href="/test-dynamic-fields.html" target="_blank">
                                {{ __('Dynamic Fields Demo') }}
                            </x-nav-link>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <p class="text-gray-600 mt-2">Real-time tracking and analysis of website visits and user behavior</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Visits -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-lg bg-gradient-primary">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Visits</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_visits']) }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="text-green-600 font-medium">+{{ $stats['today_visits'] }} today</span>
                                <span class="mx-2">•</span>
                                <span>{{ $stats['unique_visitors'] }} unique visitors</span>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Visits -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-lg bg-gradient-success">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Today's Visits</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['today_visits'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                @if($stats['yesterday_visits'] > 0)
                                    @php $change = (($stats['today_visits'] - $stats['yesterday_visits']) / $stats['yesterday_visits']) * 100 @endphp
                                    <span class="{{ $change >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $change >= 0 ? '+' : '' }}{{ round($change, 1) }}% from yesterday
                                    </span>
                                @else
                                    <span class="text-gray-500">No data for comparison</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Average Duration -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-lg bg-gradient-warning">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Avg. Duration</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['avg_duration'] }}s</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm text-gray-500">
                                Time spent per visit
                            </div>
                        </div>
                    </div>

                    <!-- Jokes Collected -->
                    <div class="stat-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-lg bg-gradient-info">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Jokes Collected</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_jokes'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <span class="text-blue-600 font-medium">+{{ $stats['jokes_today'] }} today</span>
                                <span class="mx-2">•</span>
                                <span>Auto-fetched every 5min</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Hourly Visits Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Hourly Visits (Last 24h)</h3>
                                <p class="text-sm text-gray-600">Unique vs Total visits per hour</p>
                            </div>
                            <button onclick="refreshHourlyChart()" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                ↻ Refresh
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="hourlyVisitsChart"></canvas>
                        </div>
                    </div>

                    <!-- City Distribution Chart -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">City Distribution</h3>
                                <p class="text-sm text-gray-600">Top 10 cities by visits</p>
                            </div>
                            <button onclick="refreshCityChart()" class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                                ↻ Refresh
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="cityDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Device Statistics & Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Device Statistics -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Device Statistics</h3>
                        <div class="space-y-4">
                            @foreach($deviceStats['devices'] as $index => $device)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $deviceStats['colors'][$index] }}"></div>
                                        <span class="text-gray-700">{{ $device }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-gray-900">{{ $deviceStats['counts'][$index] }}</span>
                                        <span class="text-sm text-gray-500 ml-2">({{ $deviceStats['percentages'][$index] }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
                        <div class="space-y-4">
                            @foreach($recentVisits as $visit)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                    <div>
                                        <div class="flex items-center">
                                            @if($visit->country)
                                                <span class="text-sm font-medium text-gray-900">{{ $visit->city ?: $visit->country }}</span>
                                            @else
                                                <span class="text-sm font-medium text-gray-900">Unknown Location</span>
                                            @endif
                                            <span class="mx-2 text-gray-300">•</span>
                                            <span class="text-xs text-gray-500">{{ $visit->device_type ?: 'Unknown' }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $visit->browser ?: 'Unknown browser' }} • 
                                            {{ $visit->visited_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs px-2 py-1 rounded-full 
                                            {{ $visit->duration && $visit->duration > 30000 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            @if($visit->duration)
                                                {{ round($visit->duration / 1000, 1) }}s
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Jokes -->
                <div class="mt-8 bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recently Fetched Jokes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($recentJokes as $joke)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-800 rounded-full">
                                        {{ $joke->type ?: 'general' }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $joke->fetched_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-800 font-medium mb-2">{{ $joke->setup }}</p>
                                <p class="text-gray-600 text-sm">{{ $joke->punchline }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Page Tracker Info -->
                <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm p-6 border border-blue-100">
                    <div class="flex items-start">
                        <div class="p-3 rounded-lg bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">Page Tracker Integration</h4>
                            <p class="text-gray-600 mt-1">Embed this script on any website to start tracking visits:</p>
                            <div class="mt-3 bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                                &lt;script src="{{ url('/page-tracker.js') }}"&gt;&lt;/script&gt;
                            </div>
                            <div class="mt-4 flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>GDPR compliant • IP anonymization • Real-time tracking</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize charts
        let hourlyChart = null;
        let cityChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            setupAutoRefresh();
        });

        function initializeCharts() {
            // Hourly Visits Chart
            const hourlyCtx = document.getElementById('hourlyVisitsChart').getContext('2d');
            hourlyChart = new Chart(hourlyCtx, {
                type: 'line',
                data: {
                    labels: @json($hourlyVisits['hours']),
                    datasets: [
                        {
                            label: 'Unique Visits',
                            data: @json($hourlyVisits['unique_visits']),
                            borderColor: '#4F46E5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Total Visits',
                            data: @json($hourlyVisits['total_visits']),
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // City Distribution Chart
            const cityCtx = document.getElementById('cityDistributionChart').getContext('2d');
            cityChart = new Chart(cityCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($cityDistribution['cities']),
                    datasets: [{
                        data: @json($cityDistribution['visits']),
                        backgroundColor: @json($cityDistribution['colors']),
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        function refreshHourlyChart() {
            fetch('{{ route("analytics.hourly") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        hourlyChart.data.labels = data.data.hours;
                        hourlyChart.data.datasets[0].data = data.data.unique_visits;
                        hourlyChart.data.datasets[1].data = data.data.total_visits;
                        hourlyChart.update('none');
                    }
                })
                .catch(error => console.error('Error refreshing hourly chart:', error));
        }

        function refreshCityChart() {
            fetch('{{ route("analytics.cities") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cityChart.data.labels = data.data.cities;
                        cityChart.data.datasets[0].data = data.data.visits;
                        cityChart.data.datasets[0].backgroundColor = data.data.colors;
                        cityChart.update('none');
                    }
                })
                .catch(error => console.error('Error refreshing city chart:', error));
        }

        function setupAutoRefresh() {
            // Refresh charts every 60 seconds
            setInterval(refreshHourlyChart, 60000);
            setInterval(refreshCityChart, 120000);
            
            // Refresh stats every 30 seconds
            setInterval(refreshStats, 30000);
        }

        function refreshStats() {
            fetch('{{ route("analytics.stats") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update stats cards if needed
                        console.log('Stats updated:', data.data);
                    }
                })
                .catch(error => console.error('Error refreshing stats:', error));
        }
    </script>
</body>
</html>