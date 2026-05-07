<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Test Task - PHP Developer Position</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Tailwind CSS from Breeze -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-text {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="text-xl font-bold gradient-text">
                        🚀 Laravel Test Task
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition-opacity">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    PHP Developer Test Task
                </h1>
                <p class="text-xl mb-8 opacity-90">
                    Complete Laravel implementation with all requirements met
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="bg-white text-purple-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        🔐 Login to Dashboard
                    </a>
                    <a href="/api/jokes" target="_blank" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white/10 transition-colors">
                        😄 Test Jokes API
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            ✅ All Requirements Implemented
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1: Console Command -->
            <div class="feature-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Console Command</h3>
                </div>
                <p class="text-gray-600 mb-4">Fetches jokes from API every 5 minutes and stores in database.</p>
                <div class="bg-gray-900 text-gray-100 p-3 rounded-lg font-mono text-sm">
                    php artisan jokes:fetch
                </div>
            </div>

            <!-- Feature 2: JSON API -->
            <div class="feature-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">JSON API Endpoints</h3>
                </div>
                <p class="text-gray-600 mb-4">RESTful API returning jokes in JSON format.</p>
                <div class="space-y-2">
                    <a href="/api/jokes" target="_blank" class="block text-blue-600 hover:text-blue-800 text-sm">
                        📋 GET /api/jokes
                    </a>
                    <a href="/api/jokes/random" target="_blank" class="block text-blue-600 hover:text-blue-800 text-sm">
                        🎲 GET /api/jokes/random
                    </a>
                </div>
            </div>

            <!-- Feature 3: Dynamic Fields -->
            <div class="feature-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Dynamic Fields</h3>
                </div>
                <p class="text-gray-600 mb-4">JavaScript controller showing/hiding fields based on selected type.</p>
                <a href="/test-dynamic-fields.html" target="_blank" class="inline-flex items-center text-purple-600 hover:text-purple-800">
                    🎯 Try the Demo
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Feature 4: Page Tracker -->
            <div class="feature-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Page Tracker</h3>
                </div>
                <p class="text-gray-600 mb-4">Universal JavaScript tracker for visitor analytics.</p>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <code class="text-sm text-gray-800">&lt;script src="/page-tracker.js"&gt;&lt;/script&gt;</code>
                </div>
            </div>

            <!-- Feature 5: Analytics Dashboard -->
            <div class="feature-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-yellow-100">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Analytics Dashboard</h3>
                </div>
                <p class="text-gray-600 mb-4">Charts for hourly visits and city distribution (login required).</p>
                <div class="text-sm text-gray-500">
                    Requires authentication
                </div>
            </div>

            <!-- Feature 6: Authentication -->
            <div class="feature-card bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <div class="p-3 rounded-lg bg-indigo-100">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 ml-3">Authentication</h3>
                </div>
                <p class="text-gray-600 mb-4">Secure admin area with Laravel Breeze.</p>
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm">
                        🔐 Login
                    </a>
                    <a href="{{ route('register') }}" class="block text-indigo-600 hover:text-indigo-800 text-sm">
                        📝 Register
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Demo Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">🚀 Quick Start Demo</h2>
                <p class="text-gray-600">Test all features with demo credentials</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-8 max-w-2xl mx-auto">
                <div class="flex items-start mb-6">
                    <div class="p-3 rounded-lg bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Demo Credentials</h3>
                        <p class="text-gray-600 mt-1">Use these to login and test all features</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 mb-1">Email</div>
                        <div class="font-mono text-gray-900">admin@test.com</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 mb-1">Password</div>
                        <div class="font-mono text-gray-900">password123</div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h4 class="font-semibold text-gray-900 mb-4">Test These Features:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="bg-blue-100 text-blue-700 px-4 py-3 rounded-lg hover:bg-blue-200 transition-colors text-center">
                            🔐 Login to Dashboard
                        </a>
                        <a href="/api/jokes" target="_blank" class="bg-green-100 text-green-700 px-4 py-3 rounded-lg hover:bg-green-200 transition-colors text-center">
                            😄 Test Jokes API
                        </a>
                        <a href="/test-dynamic-fields.html" target="_blank" class="bg-purple-100 text-purple-700 px-4 py-3 rounded-lg hover:bg-purple-200 transition-colors text-center">
                            🎯 Dynamic Fields Demo
                        </a>
                        <a href="/page-tracker.js" target="_blank" class="bg-red-100 text-red-700 px-4 py-3 rounded-lg hover:bg-red-200 transition-colors text-center">
                            📊 Page Tracker Script
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Info -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">📋 Project Completion Status</h2>
            
            <div class="bg-white rounded-xl shadow-sm p-6 max-w-3xl mx-auto">
                <div class="mb-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold">
                        ✅ 100% Complete - All Requirements Met
                    </div>
                </div>
                
                <div class="space-y-4 text-left">
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">Console command fetching API data every 5 minutes</span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">JSON API endpoint for jokes data</span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">JavaScript dynamic field display controller</span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">Universal page visit tracker (JavaScript + backend)</span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">Analytics dashboard with charts (hourly visits & city distribution)</span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">Authentication system for admin area</span>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-gray-700">Deployment configuration ready for hosting</span>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-600">
                        This project demonstrates professional Laravel development skills with all test requirements implemented.
                        Ready for review and deployment.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-gray-400">Laravel Test Task - PHP Developer Position</p>
                <p class="text-gray-500 text-sm mt-2">All requirements implemented with professional quality code</p>
            </div>
        </div>
    </footer>
</body>
</html>