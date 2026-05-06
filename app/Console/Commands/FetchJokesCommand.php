<?php

namespace App\Console\Commands;

use App\Models\Joke;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchJokesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jokes:fetch 
                            {--count=1 : Number of jokes to fetch (max 10)}
                            {--test : Test mode - fetch but don\'t save to database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch jokes from official-joke-api.appspot.com and store them in database';

    /**
     * API endpoint for fetching jokes.
     */
    protected string $apiUrl = 'https://official-joke-api.appspot.com';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = min((int) $this->option('count'), 10);
        $testMode = $this->option('test');

        $this->info("Fetching {$count} joke(s) from API...");

        try {
            $jokes = $this->fetchJokesFromApi($count);
            
            if (empty($jokes)) {
                $this->error('No jokes received from API.');
                return Command::FAILURE;
            }

            $savedCount = 0;
            foreach ($jokes as $jokeData) {
                if ($this->processJoke($jokeData, $testMode)) {
                    $savedCount++;
                }
            }

            $message = $testMode 
                ? "Test mode: Would have saved {$savedCount} joke(s) out of " . count($jokes)
                : "Successfully saved {$savedCount} joke(s) out of " . count($jokes);

            $this->info($message);
            Log::info('Jokes fetched from API', ['count' => $savedCount, 'total' => count($jokes)]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error fetching jokes: " . $e->getMessage());
            Log::error('Error fetching jokes from API', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }

    /**
     * Fetch jokes from the API.
     */
    protected function fetchJokesFromApi(int $count): array
    {
        $endpoint = $count === 1 
            ? "{$this->apiUrl}/random_joke"
            : "{$this->apiUrl}/random_ten";

        $response = Http::timeout(10)->get($endpoint);

        if (!$response->successful()) {
            throw new \Exception("API request failed with status: " . $response->status());
        }

        $data = $response->json();

        // API returns single object for random_joke, array for random_ten
        return $count === 1 ? [$data] : $data;
    }

    /**
     * Process and save a single joke.
     */
    protected function processJoke(array $jokeData, bool $testMode = false): bool
    {
        try {
            // Check if joke already exists in database
            $existingJoke = Joke::where('api_id', $jokeData['id'])->first();
            
            if ($existingJoke) {
                $this->warn("Joke with ID {$jokeData['id']} already exists in database.");
                return false;
            }

            $joke = [
                'api_id' => $jokeData['id'],
                'type' => $jokeData['type'] ?? 'general',
                'setup' => $jokeData['setup'],
                'punchline' => $jokeData['punchline'],
                'raw_data' => $jokeData,
                'fetched_at' => now(),
            ];

            if (!$testMode) {
                Joke::create($joke);
                $this->line("Saved joke: {$jokeData['setup']}");
            } else {
                $this->line("Would save joke: {$jokeData['setup']}");
            }

            return true;

        } catch (\Exception $e) {
            $this->error("Error processing joke: " . $e->getMessage());
            Log::error('Error processing joke', ['joke_data' => $jokeData, 'error' => $e->getMessage()]);
            return false;
        }
    }
}
