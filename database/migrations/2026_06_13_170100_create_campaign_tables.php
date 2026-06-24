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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');
            $table->json('properties')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('date');
            $table->string('venue');
            $table->foreignId('lga_id')->nullable()->constrained('lgas')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('checked_in_at')->useCurrent();
            $table->string('method')->default('manual'); // qr/manual
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('deadline');
            $table->string('status')->default('pending'); // pending, in_progress, completed, verified
            $table->string('assigned_to_role')->nullable();
            $table->foreignId('lga_id')->nullable()->constrained('lgas')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->onDelete('set null');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('door_knocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('address_description')->nullable();
            $table->string('voter_name')->nullable();
            $table->string('outcome'); // supportive/neutral/opposed/not home
            $table->text('notes')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('agent_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->integer('battery_level')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // violence, ballot stuffing, INEC official misconduct, result falsification, other
            $table->text('description');
            $table->string('urgency')->default('medium'); // low, medium, high, critical
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('status')->default('reported'); // reported, escalated, resolved
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('incident_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->onDelete('cascade');
            $table->string('path');
            $table->string('type'); // photo/video
            $table->timestamps();
        });

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('polling_unit_id')->constrained('polling_units')->onDelete('cascade');
            $table->string('ec8a_image_path');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('result_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained('results')->onDelete('cascade');
            $table->string('party');
            $table->integer('votes');
            $table->timestamps();
        });

        Schema::create('voice_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('audio_path');
            $table->text('transcript')->nullable();
            $table->integer('duration')->nullable(); // duration in seconds
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // T-shirts, caps, flyers, etc.
            $table->integer('quantity');
            $table->foreignId('lga_id')->nullable()->constrained('lgas')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->onDelete('set null');
            $table->foreignId('polling_unit_id')->nullable()->constrained('polling_units')->onDelete('set null');
            $table->timestamp('distributed_at')->useCurrent();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // sms/whatsapp
            $table->text('body');
            $table->string('audience_type'); // All Members / By LGA / By Role / Custom list
            $table->string('audience_id')->nullable(); // LGA ID, Role ID, etc.
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('image_path')->nullable();
            $table->string('category')->default('General');
            $table->boolean('is_breaking')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Jingles, Flyers, Posters, Documents, Videos
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('leaderboard_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points');
            $table->string('source_type'); // recruitment, event, task, door_knock, report
            $table->unsignedBigInteger('source_id');
            $table->timestamp('earned_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_points');
        Schema::dropIfExists('media_library');
        Schema::dropIfExists('news');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('voice_reports');
        Schema::dropIfExists('result_entries');
        Schema::dropIfExists('results');
        Schema::dropIfExists('incident_media');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('agent_locations');
        Schema::dropIfExists('door_knocks');
        Schema::dropIfExists('task_completions');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('event_attendances');
        Schema::dropIfExists('events');
        Schema::dropIfExists('activity_logs');
    }
};
