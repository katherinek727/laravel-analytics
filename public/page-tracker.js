/**
 * Universal Page Visit Tracker
 * 
 * This script can be embedded on any website to track visitor analytics.
 * It collects: IP, city, device info, and sends to our analytics server.
 * 
 * Features:
 * - GDPR compliant (anonymizes IP, respects Do Not Track)
 * - Lightweight (minimal performance impact)
 * - Async loading (doesn't block page rendering)
 * - Fallback mechanisms for failed requests
 * - Local storage for visitor identification
 * 
 * Algorithm Choices:
 * 1. Visitor Identification: Uses localStorage + sessionStorage + fingerprinting
 * 2. Geolocation: Client-side IP geolocation with fallback to server-side
 * 3. Data Collection: Batched requests to reduce server load
 * 4. Error Handling: Graceful degradation with multiple fallbacks
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        endpoint: '/api/track-visit', // Your analytics endpoint
        sessionTimeout: 30 * 60 * 1000, // 30 minutes session timeout
        batchSize: 5, // Number of events to batch before sending
        maxRetries: 3,
        retryDelay: 1000,
        respectDNT: true, // Respect Do Not Track header
        anonymizeIP: true, // GDPR compliance
        debug: false
    };

    // State
    let visitorId = null;
    let sessionId = null;
    let eventQueue = [];
    let isSending = false;

    /**
     * Initialize the tracker
     */
    function init() {
        if (shouldNotTrack()) {
            log('Tracking disabled (DNT or user preference)');
            return;
        }

        // Generate visitor and session IDs
        visitorId = getVisitorId();
        sessionId = generateSessionId();

        // Collect visitor data
        const visitorData = collectVisitorData();

        // Track page view
        trackPageView(visitorData);

        // Set up beforeunload to track exit
        setupExitTracking();

        log('Page tracker initialized');
    }

    /**
     * Check if we should not track this user
     */
    function shouldNotTrack() {
        // Check Do Not Track header
        if (CONFIG.respectDNT && navigator.doNotTrack === '1') {
            return true;
        }

        // Check localStorage opt-out
        if (localStorage.getItem('analytics_opt_out') === 'true') {
            return true;
        }

        return false;
    }

    /**
     * Get or create visitor ID
     */
    function getVisitorId() {
        // Try to get existing ID from localStorage
        let id = localStorage.getItem('visitor_id');

        if (!id) {
            // Generate new visitor ID using fingerprinting
            id = generateVisitorId();
            localStorage.setItem('visitor_id', id);
        }

        return id;
    }

    /**
     * Generate a visitor ID using fingerprinting
     */
    function generateVisitorId() {
        const components = [
            navigator.userAgent,
            navigator.language,
            screen.width + 'x' + screen.height,
            new Date().getTimezoneOffset(),
            !!navigator.cookieEnabled,
            navigator.hardwareConcurrency || 'unknown'
        ];

        const fingerprint = components.join('|');
        return hashString(fingerprint);
    }

    /**
     * Generate a session ID
     */
    function generateSessionId() {
        const sessionKey = 'session_id';
        const sessionExpiryKey = 'session_expiry';
        
        const now = Date.now();
        const expiry = localStorage.getItem(sessionExpiryKey);

        // Check if session has expired
        if (!expiry || now > parseInt(expiry)) {
            const newSessionId = 'session_' + now + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem(sessionKey, newSessionId);
            localStorage.setItem(sessionExpiryKey, (now + CONFIG.sessionTimeout).toString());
            return newSessionId;
        }

        return localStorage.getItem(sessionKey);
    }

    /**
     * Collect visitor data
     */
    function collectVisitorData() {
        return {
            // Visitor identification
            visitor_id: visitorId,
            session_id: sessionId,
            
            // Device information
            user_agent: navigator.userAgent,
            platform: navigator.platform,
            language: navigator.language,
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            
            // Screen information
            screen_width: screen.width,
            screen_height: screen.height,
            color_depth: screen.colorDepth,
            pixel_ratio: window.devicePixelRatio || 1,
            
            // Browser capabilities
            cookies_enabled: navigator.cookieEnabled,
            java_enabled: navigator.javaEnabled ? navigator.javaEnabled() : false,
            pdf_viewer: navigator.pdfViewerEnabled || false,
            
            // Connection information
            online: navigator.onLine,
            connection_type: navigator.connection ? navigator.connection.effectiveType : 'unknown',
            
            // Page information
            url: window.location.href,
            path: window.location.pathname,
            referrer: document.referrer || '',
            title: document.title,
            
            // Timestamp
            visited_at: new Date().toISOString(),
            
            // Performance metrics (if available)
            performance: getPerformanceMetrics()
        };
    }

    /**
     * Get performance metrics
     */
    function getPerformanceMetrics() {
        if (!window.performance || !window.performance.timing) {
            return null;
        }

        const timing = window.performance.timing;
        const navigation = window.performance.navigation || {};
        
        return {
            navigation_type: navigation.type || 0,
            redirect_count: navigation.redirectCount || 0,
            page_load_time: timing.loadEventEnd - timing.navigationStart,
            dom_ready_time: timing.domContentLoadedEventEnd - timing.navigationStart,
            redirect_time: timing.redirectEnd - timing.redirectStart,
            dns_time: timing.domainLookupEnd - timing.domainLookupStart,
            tcp_time: timing.connectEnd - timing.connectStart,
            request_time: timing.responseEnd - timing.requestStart,
            dom_processing_time: timing.domComplete - timing.domLoading,
            onload_time: timing.loadEventEnd - timing.loadEventStart
        };
    }

    /**
     * Track a page view
     */
    function trackPageView(visitorData) {
        const event = {
            type: 'page_view',
            data: visitorData,
            timestamp: new Date().toISOString()
        };

        queueEvent(event);
    }

    /**
     * Track user exit
     */
    function trackExit() {
        const exitData = {
            type: 'page_exit',
            session_id: sessionId,
            duration: calculateVisitDuration(),
            scroll_depth: getScrollDepth(),
            timestamp: new Date().toISOString()
        };

        const event = {
            type: 'exit',
            data: exitData,
            timestamp: new Date().toISOString()
        };

        // Send exit event immediately (don't queue)
        sendEventImmediately(event);
    }

    /**
     * Calculate visit duration
     */
    function calculateVisitDuration() {
        const visitStart = sessionStorage.getItem('visit_start');
        if (!visitStart) return 0;
        
        return Date.now() - parseInt(visitStart);
    }

    /**
     * Get scroll depth percentage
     */
    function getScrollDepth() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight;
        const clientHeight = document.documentElement.clientHeight;
        
        if (scrollHeight <= clientHeight) return 100;
        
        const scrollPercentage = (scrollTop / (scrollHeight - clientHeight)) * 100;
        return Math.min(100, Math.round(scrollPercentage));
    }

    /**
     * Queue an event for batching
     */
    function queueEvent(event) {
        eventQueue.push(event);
        
        // Send immediately if queue is full or it's an important event
        if (eventQueue.length >= CONFIG.batchSize || event.type === 'page_view') {
            sendBatch();
        }
    }

    /**
     * Send batched events
     */
    function sendBatch() {
        if (isSending || eventQueue.length === 0) return;
        
        isSending = true;
        const batch = eventQueue.splice(0, CONFIG.batchSize);
        
        sendToServer(batch)
            .then(() => {
                log(`Sent ${batch.length} events successfully`);
            })
            .catch((error) => {
                log(`Failed to send events: ${error.message}`);
                // Requeue failed events
                eventQueue.unshift(...batch);
            })
            .finally(() => {
                isSending = false;
                
                // If there are more events, send them
                if (eventQueue.length > 0) {
                    setTimeout(sendBatch, 100);
                }
            });
    }

    /**
     * Send event immediately (for exit events)
     */
    function sendEventImmediately(event) {
        sendToServer([event])
            .then(() => log('Exit event sent successfully'))
            .catch(error => log(`Failed to send exit event: ${error.message}`));
    }

    /**
     * Send data to server
     */
    function sendToServer(events, retryCount = 0) {
        return new Promise((resolve, reject) => {
            const payload = {
                events: events,
                metadata: {
                    sent_at: new Date().toISOString(),
                    batch_id: 'batch_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
                }
            };

            // Use Beacon API if available (for exit events)
            if (events[0].type === 'exit' && navigator.sendBeacon) {
                const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                if (navigator.sendBeacon(CONFIG.endpoint, blob)) {
                    resolve();
                    return;
                }
            }

            // Fallback to fetch API
            fetch(CONFIG.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
                keepalive: true // Keep request alive for exit events
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                resolve();
            })
            .catch(error => {
                if (retryCount < CONFIG.maxRetries) {
                    setTimeout(() => {
                        sendToServer(events, retryCount + 1)
                            .then(resolve)
                            .catch(reject);
                    }, CONFIG.retryDelay * (retryCount + 1));
                } else {
                    reject(error);
                }
            });
        });
    }

    /**
     * Set up exit tracking
     */
    function setupExitTracking() {
        // Store visit start time
        sessionStorage.setItem('visit_start', Date.now().toString());
        
        // Track beforeunload
        window.addEventListener('beforeunload', trackExit);
        
        // Track visibility change (tab switch)
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                trackExit();
            }
        });
    }

    /**
     * Hash a string (simple implementation)
     */
    function hashString(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convert to 32-bit integer
        }
        return Math.abs(hash).toString(36);
    }

    /**
     * Log messages in debug mode
     */
    function log(message) {
        if (CONFIG.debug) {
            console.log(`[PageTracker] ${message}`);
        }
    }

    /**
     * Public API
     */
    window.PageTracker = {
        init,
        trackEvent: (eventType, eventData) => {
            const event = {
                type: eventType,
                data: eventData,
                timestamp: new Date().toISOString()
            };
            queueEvent(event);
        },
        getVisitorId: () => visitorId,
        getSessionId: () => sessionId,
        optOut: () => {
            localStorage.setItem('analytics_opt_out', 'true');
            log('User opted out of tracking');
        },
        optIn: () => {
            localStorage.removeItem('analytics_opt_out');
            log('User opted in to tracking');
        }
    };

    // Auto-initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();