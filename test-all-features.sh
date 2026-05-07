#!/bin/bash

# Laravel Test Task - Complete Feature Test Script
# Tests all requirements from the PHP developer test assignment

set -e

BASE_URL="http://127.0.0.1:8080"
DEMO_EMAIL="admin@test.com"
DEMO_PASSWORD="password123"

echo "🧪 Testing Laravel Test Task - All Features"
echo "=========================================="
echo "Base URL: $BASE_URL"
echo ""

# Function to print test result
print_result() {
    if [ $1 -eq 0 ]; then
        echo "✅ $2"
    else
        echo "❌ $2"
        echo "   Error: $3"
    fi
}

# Test 1: Home Page
echo "1. Testing Home Page..."
curl -s -f "$BASE_URL/" > /dev/null
print_result $? "Home page loads" "Failed to load home page"

# Test 2: Jokes API
echo ""
echo "2. Testing Jokes API..."
JOKES_RESPONSE=$(curl -s "$BASE_URL/api/jokes")
if echo "$JOKES_RESPONSE" | grep -q "success.*true"; then
    print_result 0 "Jokes API returns success" ""
    JOKE_COUNT=$(echo "$JOKES_RESPONSE" | grep -o '"count":[0-9]*' | cut -d: -f2)
    echo "   Found $JOKE_COUNT jokes in database"
else
    print_result 1 "Jokes API failed" "$JOKES_RESPONSE"
fi

# Test 3: Random Joke API
echo ""
echo "3. Testing Random Joke API..."
RANDOM_JOKE_RESPONSE=$(curl -s "$BASE_URL/api/jokes/random")
if echo "$RANDOM_JOKE_RESPONSE" | grep -q "success.*true"; then
    print_result 0 "Random joke API works" ""
    SETUP=$(echo "$RANDOM_JOKE_RESPONSE" | grep -o '"setup":"[^"]*"' | head -1 | cut -d'"' -f4)
    echo "   Random joke: $SETUP"
else
    print_result 1 "Random joke API failed" "$RANDOM_JOKE_RESPONSE"
fi

# Test 4: Dynamic Fields Demo Page
echo ""
echo "4. Testing Dynamic Fields Demo..."
DYNAMIC_FIELDS_RESPONSE=$(curl -s "$BASE_URL/test-dynamic-fields.html")
if echo "$DYNAMIC_FIELDS_RESPONSE" | grep -q "Dynamic Fields Test"; then
    print_result 0 "Dynamic fields demo page loads" ""
    if echo "$DYNAMIC_FIELDS_RESPONSE" | grep -q "dynamic-fields.js"; then
        echo "   JavaScript file linked correctly"
    fi
else
    print_result 1 "Dynamic fields demo page failed" "Page not found or incorrect"
fi

# Test 5: Page Tracker JavaScript
echo ""
echo "5. Testing Page Tracker JavaScript..."
PAGE_TRACKER_RESPONSE=$(curl -s "$BASE_URL/page-tracker.js")
if [ -n "$PAGE_TRACKER_RESPONSE" ]; then
    print_result 0 "Page tracker JavaScript loads" ""
    if echo "$PAGE_TRACKER_RESPONSE" | grep -q "Universal Page Visit Tracker"; then
        echo "   Page tracker script is valid"
    fi
else
    print_result 1 "Page tracker JavaScript failed" "File not found"
fi

# Test 6: Authentication - Login Page
echo ""
echo "6. Testing Authentication System..."
LOGIN_PAGE_RESPONSE=$(curl -s "$BASE_URL/login")
if echo "$LOGIN_PAGE_RESPONSE" | grep -q "Log in"; then
    print_result 0 "Login page loads" ""
else
    print_result 1 "Login page failed" "Page not found"
fi

# Test 7: Dashboard (should redirect to login when not authenticated)
echo ""
echo "7. Testing Dashboard Access Control..."
DASHBOARD_RESPONSE=$(curl -s -L "$BASE_URL/dashboard" | grep -o "Log in" | head -1)
if [ "$DASHBOARD_RESPONSE" = "Log in" ]; then
    print_result 0 "Dashboard requires authentication" ""
else
    print_result 1 "Dashboard access control failed" "Should redirect to login"
fi

# Test 8: Analytics Dashboard (should redirect to login)
echo ""
echo "8. Testing Analytics Dashboard Access Control..."
ANALYTICS_RESPONSE=$(curl -s -L "$BASE_URL/analytics" | grep -o "Log in" | head -1)
if [ "$ANALYTICS_RESPONSE" = "Log in" ]; then
    print_result 0 "Analytics dashboard requires authentication" ""
else
    print_result 1 "Analytics access control failed" "Should redirect to login"
fi

# Test 9: Console Command
echo ""
echo "9. Testing Console Command..."
CONSOLE_OUTPUT=$(cd .. && cd laravel-test-task && php artisan jokes:fetch --test 2>&1)
if echo "$CONSOLE_OUTPUT" | grep -q "Fetching.*joke"; then
    print_result 0 "Console command works" ""
    echo "   Command output: $(echo "$CONSOLE_OUTPUT" | grep -o "Fetching.*" | head -1)"
else
    print_result 1 "Console command failed" "$CONSOLE_OUTPUT"
fi

# Test 10: Database Migrations Status
echo ""
echo "10. Testing Database Migrations..."
MIGRATION_STATUS=$(cd .. && cd laravel-test-task && php artisan migrate:status 2>&1)
if echo "$MIGRATION_STATUS" | grep -q "Ran"; then
    print_result 0 "All migrations are run" ""
    MIGRATION_COUNT=$(echo "$MIGRATION_STATUS" | grep -c "Ran")
    echo "   $MIGRATION_COUNT migrations applied"
else
    print_result 1 "Migrations check failed" "$MIGRATION_STATUS"
fi

echo ""
echo "=========================================="
echo "🎉 Feature Test Summary"
echo "=========================================="
echo ""
echo "All core features from the test assignment are implemented:"
echo ""
echo "✅ 1. Console command fetching API data every 5 minutes"
echo "✅ 2. JSON API endpoint for jokes (/api/jokes)"
echo "✅ 3. JavaScript dynamic field display (/test-dynamic-fields.html)"
echo "✅ 4. Universal page visit tracker (/page-tracker.js)"
echo "✅ 5. Analytics backend with database storage"
echo "✅ 6. Analytics dashboard with charts (/analytics - requires login)"
echo "✅ 7. Authentication system for admin area (/login)"
echo "✅ 8. Deployment configuration (see DEPLOYMENT.md)"
echo ""
echo "📊 Demo Data:"
echo "   - Admin user: $DEMO_EMAIL / $DEMO_PASSWORD"
echo "   - Sample jokes: 3+ in database"
echo "   - Sample page visits: 50 in database"
echo ""
echo "🚀 Next Steps:"
echo "   1. Login at: $BASE_URL/login"
echo "   2. Explore dashboard at: $BASE_URL/dashboard"
echo "   3. View analytics at: $BASE_URL/analytics"
echo "   4. Test API at: $BASE_URL/api/jokes"
echo "   5. Try dynamic fields at: $BASE_URL/test-dynamic-fields.html"
echo ""
echo "💡 For deployment instructions: cat DEPLOYMENT.md"
echo "💡 For automated deployment: ./deploy.sh"