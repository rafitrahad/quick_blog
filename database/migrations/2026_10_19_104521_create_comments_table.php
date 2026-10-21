<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 1. Parent ID for threaded replies (NULL = top-level comment)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            
            $table->text('body');
            
            // 2. Placeholder for reactions (JSON format, e.g., {"likes": 5, "hearts": 2})
            $table->json('reactions')->nullable(); 

            // 3. Timestamp when comment was last edited
            $table->timestamp('edited_at')->nullable();

            // 4. User IDs who hid/removed this comment from their personal view
            $table->json('hidden_by')->nullable();

            $table->timestamps();
            
            // 5. Soft deletes (removes comment publicly, retains in database for security)
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
