<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable()->comment('Unique session identifier');
            $table->string('visitor_id')->nullable()->comment('Unique visitor identifier (hashed)');
            
            // IP and location data
            $table->string('ip_address', 45)->nullable()->comment('Visitor IP address');
            $table->string('country')->nullable()->comment('Country from IP geolocation');
            $table->string('city')->nullable()->comment('City from IP geolocation');
            $table->string('region')->nullable()->comment('Region/state from IP geolocation');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Geolocation latitude');
            $table->decimal('longitude', 10, 8)->nullable()->comment('Geolocation longitude');
            
            // Device and browser data
            $table->string('user_agent')->nullable()->comment('Full user agent string');
            $table->string('browser')->nullable()->comment('Browser name');
            $table->string('browser_version')->nullable()->comment('Browser version');
            $table->string('platform')->nullable()->comment('Operating system/platform');
            $table->string('device_type')->nullable()->comment('Desktop, Mobile, Tablet, etc.');
            $table->boolean('is_mobile')->default(false)->comment('Is mobile device');
            $table->boolean('is_tablet')->default(false)->comment('Is tablet device');
            $table->boolean('is_desktop')->default(false)->comment('Is desktop device');
            $table->boolean('is_bot')->default(false)->comment('Is search engine bot');
            
            // Page and referrer data
            $table->string('url')->comment('Page URL visited');
            $table->string('path')->comment('URL path');
            $table->string('referrer')->nullable()->comment('Referrer URL');
            $table->string('referrer_domain')->nullable()->comment('Referrer domain');
            
            // Screen data
            $table->integer('screen_width')->nullable()->comment('Screen width in pixels');
            $table->integer('screen_height')->nullable()->comment('Screen height in pixels');
            $table->string('language')->nullable()->comment('Browser language');
            $table->string('timezone')->nullable()->comment('Visitor timezone');
            
            // Engagement data
            $table->integer('page_load_time')->nullable()->comment('Page load time in ms');
            $table->timestamp('visited_at')->useCurrent()->comment('Timestamp of visit');
            $table->timestamp('left_at')->nullable()->comment('Timestamp when visitor left');
            $table->integer('duration')->nullable()->comment('Visit duration in seconds');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('session_id');
            $table->index('visitor_id');
            $table->index('ip_address');
            $table->index('country');
            $table->index('city');
            $table->index('visited_at');
            $table->index(['visited_at', 'country']);
            $table->index(['device_type', 'visited_at']);
            $table->index('url');
            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
