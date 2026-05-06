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
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique()->comment('Unique ID from the external API');
            $table->string('type')->nullable()->comment('Type of joke (general, programming, etc.)');
            $table->text('setup')->comment('The setup/question part of the joke');
            $table->text('punchline')->comment('The punchline/answer part of the joke');
            $table->json('raw_data')->nullable()->comment('Raw JSON response from API for debugging');
            $table->timestamp('fetched_at')->useCurrent()->comment('When the joke was fetched from API');
            $table->timestamps();
            
            $table->index('type');
            $table->index('fetched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jokes');
    }
};
