<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Joke;
use App\Models\PageVisit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo admin user
        $admin = User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        echo "✅ Created demo admin user: admin@test.com / password123\n";

        // Create some sample jokes if none exist
        if (Joke::count() === 0) {
            $jokes = [
                [
                    'api_id' => '1',
                    'type' => 'general',
                    'setup' => 'Why don\'t scientists trust atoms?',
                    'punchline' => 'Because they make up everything!',
                    'fetched_at' => now(),
                ],
                [
                    'api_id' => '2',
                    'type' => 'programming',
                    'setup' => 'Why do programmers prefer dark mode?',
                    'punchline' => 'Because light attracts bugs!',
                    'fetched_at' => now()->subHours(1),
                ],
                [
                    'api_id' => '3',
                    'type' => 'dad',
                    'setup' => 'What do you call a fake noodle?',
                    'punchline' => 'An impasta!',
                    'fetched_at' => now()->subHours(2),
                ],
            ];

            foreach ($jokes as $joke) {
                Joke::create($joke);
            }

            echo "✅ Created 3 sample jokes\n";
        }

        // Create some sample page visits if none exist
        if (PageVisit::count() === 0) {
            $cities = ['New York', 'London', 'Tokyo', 'Paris', 'Berlin', 'Sydney', 'Toronto', 'Singapore'];
            $devices = ['desktop', 'mobile', 'tablet'];
            $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
            
            $now = now();
            
            for ($i = 0; $i < 50; $i++) {
                $visitedAt = $now->copy()->subHours(rand(1, 48))->subMinutes(rand(0, 59));
                
                PageVisit::create([
                    'session_id' => 'session_' . uniqid(),
                    'visitor_id' => 'visitor_' . rand(1000, 9999),
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'country' => 'US',
                    'city' => $cities[array_rand($cities)],
                    'user_agent' => 'Mozilla/5.0 (' . $devices[array_rand($devices)] . ')',
                    'browser' => $browsers[array_rand($browsers)],
                    'device_type' => $devices[array_rand($devices)],
                    'is_mobile' => rand(0, 1),
                    'is_desktop' => rand(0, 1),
                    'url' => 'https://example.com/page' . rand(1, 5),
                    'path' => '/page' . rand(1, 5),
                    'screen_width' => rand(800, 1920),
                    'screen_height' => rand(600, 1080),
                    'language' => 'en-US',
                    'timezone' => 'America/New_York',
                    'visited_at' => $visitedAt,
                    'duration' => rand(1000, 60000), // 1-60 seconds in milliseconds
                    'left_at' => $visitedAt->copy()->addSeconds(rand(5, 120)),
                ]);
            }

            echo "✅ Created 50 sample page visits\n";
        }

        echo "\n🎉 Demo data setup complete!\n";
        echo "=============================\n";
        echo "Admin Login: admin@test.com\n";
        echo "Password: password123\n";
        echo "\nFeatures to test:\n";
        echo "1. Login at /login\n";
        echo "2. Dashboard at /dashboard\n";
        echo "3. Analytics at /analytics\n";
        echo "4. Jokes API at /api/jokes\n";
        echo "5. Dynamic fields demo at /test-dynamic-fields.html\n";
    }
}