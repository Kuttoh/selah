<?php

use App\Enums\PrayerStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->string('status')->default(PrayerStatus::Received->value)->after('public_token');
        });

        // Backfill status from is_prayed_for
        DB::table('prayer_requests')
            ->where('is_prayed_for', true)
            ->update(['status' => PrayerStatus::Prayed->value]);

        DB::table('prayer_requests')
            ->where('is_prayed_for', false)
            ->update(['status' => PrayerStatus::Received->value]);

        // Drop the old columns
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->dropColumn(['is_prayed_for', 'last_prayed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->boolean('is_prayed_for')->default(false)->after('public_token');
            $table->timestamp('last_prayed_at')->nullable()->after('is_prayed_for');
            $table->dropColumn('status');
        });

        // Backfill old columns from status
        DB::table('prayer_requests')
            ->where('status', PrayerStatus::Prayed->value)
            ->update(['is_prayed_for' => true]);
    }
};
