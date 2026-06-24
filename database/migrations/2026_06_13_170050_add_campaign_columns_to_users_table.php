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
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->unique()->after('email');
            $table->foreignId('lga_id')->nullable()->after('phone')->constrained('lgas')->onDelete('set null');
            $table->foreignId('ward_id')->nullable()->after('lga_id')->constrained('wards')->onDelete('set null');
            $table->foreignId('polling_unit_id')->nullable()->after('ward_id')->constrained('polling_units')->onDelete('set null');
            $table->string('status')->default('active')->after('polling_unit_id'); // active, pending_approval, suspended
            $table->string('device_token')->nullable()->after('status');
            $table->timestamp('last_seen_at')->nullable()->after('device_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropForeign(['polling_unit_id']);
            $table->dropForeign(['ward_id']);
            $table->dropForeign(['lga_id']);
            $table->dropColumn([
                'phone',
                'lga_id',
                'ward_id',
                'polling_unit_id',
                'status',
                'device_token',
                'last_seen_at'
            ]);
        });
    }
};
