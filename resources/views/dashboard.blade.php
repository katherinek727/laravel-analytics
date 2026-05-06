<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Welcome to the Test Task Dashboard!</h3>
                            <p class="text-gray-600 mt-1">All features from the PHP developer test assignment are now implemented.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Analytics Dashboard -->
                <a href="{{ route('analytics.dashboard') }}" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:border-blue-300 transition-colors h-full">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-2 rounded-lg bg-blue-100 group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 ml-3">Analytics Dashboard</h4>
                            </div>
                            <p class="text-gray-600">View real-time visitor statistics, charts, and tracking data with hourly visit graphs and city distribution.</p>
                            <div class="mt-4 flex items-center text-blue-600 group-hover:text-blue-700">
                                <span>Go to Analytics</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Jokes API -->
                <a href="/api/jokes" target="_blank" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:border-green-300 transition-colors h-full">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-2 rounded-lg bg-green-100 group-hover:bg-green-200 transition-colors">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 ml-3">Jokes API</h4>
                            </div>
                            <p class="text-gray-600">Access the JSON API with jokes fetched every 5 minutes from official-joke-api.appspot.com.</p>
                            <div class="mt-4 flex items-center text-green-600 group-hover:text-green-700">
                                <span>View API Endpoints</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Dynamic Fields Demo -->
                <a href="/test-dynamic-fields.html" target="_blank" class="block group">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:border-purple-300 transition-colors h-full">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="p-2 rounded-lg bg-purple-100 group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 ml-3">Dynamic Fields Demo</h4>
                            </div>
                            <p class="text-gray-600">Test the JavaScript dynamic field controller that shows/hides form fields based on selected type.</p>
                            <div class="mt-4 flex items-center text-purple-600 group-hover:text-purple-700">
                                <span>Try the Demo</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Page Tracker -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 h-full">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg bg-indigo-100">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 ml-3">Page Tracker</h4>
                        </div>
                        <p class="text-gray-600">Universal JavaScript tracker that can be embedded on any website to collect visitor analytics.</p>
                        <div class="mt-4 bg-gray-50 p-3 rounded-lg">
                            <code class="text-sm text-gray-800">&lt;script src="{{ url('/page-tracker.js') }}"&gt;&lt;/script&gt;</code>
                        </div>
                    </div>
                </div>

                <!-- Console Command -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 h-full">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg bg-amber-100">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 ml-3">Console Command</h4>
                        </div>
                        <p class="text-gray-600">Automatically fetches jokes from API every 5 minutes. Run manually:</p>
                        <div class="mt-3 bg-gray-900 text-gray-100 p-3 rounded-lg font-mono text-sm">
                            php artisan jokes:fetch
                        </div>
                    </div>
                </div>

                <!-- Database -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 h-full">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg bg-red-100">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 ml-3">Database</h4>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jokes stored:</span>
                                <span class="font-semibold">{{ App\Models\Joke::count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Page visits:</span>
                                <span class="font-semibold">{{ App\Models\PageVisit::count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Unique visitors:</span>
                                <span class="font-semibold">{{ App\Models\PageVisit::distinct('visitor_id')->count('visitor_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Status: Complete ✅</h3>
                <p class="text-gray-700 mb-3">All requirements from the PHP developer test assignment have been implemented:</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>✅ Console command fetching API data every 5 minutes</li>
                    <li>✅ JSON API endpoint for jokes data</li>
                    <li>✅ JavaScript dynamic field display controller</li>
                    <li>✅ Universal page visit tracker (JavaScript + backend)</li>
                    <li>✅ Analytics dashboard with charts (hourly visits, city distribution)</li>
                    <li>✅ Authentication system for admin area</li>
                    <li>✅ SQLite database with proper schema</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
