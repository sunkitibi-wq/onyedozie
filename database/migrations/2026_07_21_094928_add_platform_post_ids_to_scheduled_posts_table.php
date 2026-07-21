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
        Schema::table('scheduled_posts', function (Blueprint $table) {
            $table->string('facebook_post_id')->nullable()->after('platforms');
            $table->string('instagram_post_id')->nullable()->after('facebook_post_id');
            $table->string('x_post_id')->nullable()->after('instagram_post_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            $table->dropColumn(['facebook_post_id', 'instagram_post_id', 'x_post_id']);
        });
    }
};
