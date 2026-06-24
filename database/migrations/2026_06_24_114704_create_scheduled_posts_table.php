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
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->json('media_paths')->nullable(); // up to 4 images
            $table->json('platforms'); // ['facebook', 'twitter', 'instagram']
            $table->dateTime('scheduled_at');
            $table->string('status')->default('scheduled'); // draft, scheduled, posted, failed
            $table->text('error_message')->nullable();
            $table->integer('engagement_likes')->default(0);
            $table->integer('engagement_shares')->default(0);
            $table->integer('engagement_reach')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};
