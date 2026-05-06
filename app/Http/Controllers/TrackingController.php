<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    /**
     * Handle incoming tracking requests from the JavaScript tracker.
     */
    public function track(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'events' => 'required|array',
            'events.*.type' => 'required|string',
            'events.*.data' => 'required|array',
            'events.*.timestamp' => 'required|date',
            'metadata.batch_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid tracking request', [
                'errors' => $validator->errors(),
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $events = $request->input('events');
            $batchId = $request->input('metadata.batch_id');
            $processedCount = 0;

            foreach ($events as $event) {
                if ($this->processEvent($event, $request)) {
                    $processedCount++;
                }
            }

            Log::info('Tracking batch processed', [
                'batch_id' => $batchId,
                'total_events' => count($events),
                'processed' => $processedCount,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking data received',
                'processed' => $processedCount,
                'total' => count($events),
                'batch_id' => $batchId
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing tracking batch', [
                'error' => $e->getMessage(),
                'batch_id' => $batchId ?? 'unknown',
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a single tracking event.
     */
    protected function processEvent(array $event, Request $request): bool
    {
        try {
            $eventType = $event['type'];
            $eventData = $event['data'];
            $timestamp = $event['timestamp'];

            switch ($eventType) {
                case 'page_view':
                    return $this->processPageView($eventData, $request, $timestamp);
                
                case 'exit':
                    return $this->processExitEvent($eventData, $timestamp);
                
                default:
                    Log::debug('Unknown event type', ['type' => $eventType]);
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Error processing event', [
                'event_type' => $event['type'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Process a page view event.
     */
    protected function processPageView(array $data, Request $request, string $timestamp): bool
    {
        // Get geolocation data from IP
        $ipAddress = $request->ip();
        $geolocation = $this->getGeolocation($ipAddress);

        // Parse user agent
        $userAgent = $data['user_agent'] ?? '';
        $deviceInfo = $this->parseUserAgent($userAgent);

        // Create page visit record
        $pageVisit = PageVisit::create([
            'session_id' => $data['session_id'] ?? Str::random(32),
            'visitor_id' => $data['visitor_id'] ?? $this->generateVisitorId($data),
            
            // IP and location
            'ip_address' => $this->anonymizeIp($ipAddress),
            'country' => $geolocation['country'] ?? null,
            'city' => $geolocation['city'] ?? null,
            'region' => $geolocation['region'] ?? null,
            'latitude' => $geolocation['latitude'] ?? null,
            'longitude' => $geolocation['longitude'] ?? null,
            
            // Device and browser
            'user_agent' => $userAgent,
            'browser' => $deviceInfo['browser'] ?? null,
            'browser_version' => $deviceInfo['browser_version'] ?? null,
            'platform' => $deviceInfo['platform'] ?? null,
            'device_type' => $deviceInfo['device_type'] ?? 'desktop',
            'is_mobile' => $deviceInfo['is_mobile'] ?? false,
            'is_tablet' => $deviceInfo['is_tablet'] ?? false,
            'is_desktop' => $deviceInfo['is_desktop'] ?? true,
            'is_bot' => $deviceInfo['is_bot'] ?? false,
            
            // Page information
            'url' => $data['url'] ?? '',
            'path' => $data['path'] ?? '/',
            'referrer' => $data['referrer'] ?? null,
            'referrer_domain' => $this->extractDomain($data['referrer'] ?? ''),
            
            // Screen and language
            'screen_width' => $data['screen_width'] ?? null,
            'screen_height' => $data['screen_height'] ?? null,
            'language' => $data['language'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            
            // Performance
            'page_load_time' => $data['performance']['page_load_time'] ?? null,
            
            // Timestamps
            'visited_at' => $timestamp,
        ]);

        return $pageVisit->exists;
    }

    /**
     * Process an exit event.
     */
    protected function processExitEvent(array $data, string $timestamp): bool
    {
        $sessionId = $data['session_id'] ?? null;
        
        if (!$sessionId) {
            return false;
        }

        // Find the most recent page visit for this session
        $pageVisit = PageVisit::where('session_id', $sessionId)
            ->orderBy('visited_at', 'desc')
            ->first();

        if (!$pageVisit) {
            return false;
        }

        // Update with exit data
        $pageVisit->update([
            'left_at' => $timestamp,
            'duration' => $data['duration'] ?? null,
        ]);

        return true;
    }

    /**
     * Get geolocation data from IP address.
     */
    protected function getGeolocation(string $ip): array
    {
        // For production, you would use a service like ipapi, ipstack, or MaxMind
        // This is a simplified version for demonstration
        
        if ($this->isLocalIp($ip)) {
            return [
                'country' => 'Local',
                'city' => 'Local Network',
                'region' => 'Internal',
            ];
        }

        // In a real application, you would call a geolocation API here
        // For now, return empty data
        return [];
    }

    /**
     * Parse user agent string to extract device information.
     */
    protected function parseUserAgent(string $userAgent): array
    {
        $result = [
            'browser' => 'Unknown',
            'browser_version' => null,
            'platform' => 'Unknown',
            'device_type' => 'desktop',
            'is_mobile' => false,
            'is_tablet' => false,
            'is_desktop' => true,
            'is_bot' => false,
        ];

        // Simple detection (in production, use a library like jenssegers/agent)
        $ua = strtolower($userAgent);
        
        // Detect bots
        if (str_contains($ua, 'bot') || str_contains($ua, 'crawler') || str_contains($ua, 'spider')) {
            $result['is_bot'] = true;
            $result['device_type'] = 'bot';
            return $result;
        }

        // Detect mobile devices
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            $result['is_mobile'] = true;
            $result['device_type'] = 'mobile';
            $result['is_desktop'] = false;
        }
        
        // Detect tablets
        elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            $result['is_tablet'] = true;
            $result['device_type'] = 'tablet';
            $result['is_desktop'] = false;
        }

        // Detect browsers
        if (str_contains($ua, 'chrome')) {
            $result['browser'] = 'Chrome';
        } elseif (str_contains($ua, 'firefox')) {
            $result['browser'] = 'Firefox';
        } elseif (str_contains($ua, 'safari')) {
            $result['browser'] = 'Safari';
        } elseif (str_contains($ua, 'edge')) {
            $result['browser'] = 'Edge';
        }

        // Detect platform
        if (str_contains($ua, 'windows')) {
            $result['platform'] = 'Windows';
        } elseif (str_contains($ua, 'mac')) {
            $result['platform'] = 'macOS';
        } elseif (str_contains($ua, 'linux')) {
            $result['platform'] = 'Linux';
        } elseif (str_contains($ua, 'android')) {
            $result['platform'] = 'Android';
        } elseif (str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) {
            $result['platform'] = 'iOS';
        }

        return $result;
    }

    /**
     * Generate a visitor ID from tracking data.
     */
    protected function generateVisitorId(array $data): string
    {
        $components = [
            $data['user_agent'] ?? '',
            $data['screen_width'] ?? '',
            $data['screen_height'] ?? '',
            $data['language'] ?? '',
            $data['timezone'] ?? '',
        ];

        $fingerprint = implode('|', $components);
        return hash('sha256', $fingerprint);
    }

    /**
     * Anonymize IP address for GDPR compliance.
     */
    protected function anonymizeIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // For IPv4, zero out the last octet
            return preg_replace('/\.\d+$/', '.0', $ip);
        }
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // For IPv6, zero out the last 80 bits
            $parts = explode(':', $ip);
            if (count($parts) >= 5) {
                $parts[4] = '0000';
                for ($i = 5; $i < count($parts); $i++) {
                    $parts[$i] = '0000';
                }
                return implode(':', $parts);
            }
        }
        
        return $ip;
    }

    /**
     * Extract domain from URL.
     */
    protected function extractDomain(string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $parsed = parse_url($url);
        return $parsed['host'] ?? null;
    }

    /**
     * Check if IP is local/private.
     */
    protected function isLocalIp(string $ip): bool
    {
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            '127.0.0.0/8',
            '::1/128',
            'fc00::/7',
        ];

        foreach ($privateRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is in range.
     */
    protected function ipInRange(string $ip, string $range): bool
    {
        if (str_contains($range, '/')) {
            list($subnet, $bits) = explode('/', $range);
            $ip = inet_pton($ip);
            $subnet = inet_pton($subnet);
            
            if ($ip === false || $subnet === false) {
                return false;
            }
            
            $mask = -1 << (128 - $bits);
            if ($ip instanceof \GMP) {
                $ip = gmp_strval($ip, 10);
                $subnet = gmp_strval($subnet, 10);
                $mask = gmp_strval($mask, 10);
                return gmp_cmp(gmp_and($ip, $mask), gmp_and($subnet, $mask)) === 0;
            }
            
            return ($ip & $mask) == ($subnet & $mask);
        }
        
        return $ip === $range;
    }
}
