<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // Foreign Key
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Notification Data
            $table->string('type'); // e.g., 'new_reply', 'thread_mention'
            $table->json('data');   // Store necessary details (thread_id, post_id, etc.)
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};