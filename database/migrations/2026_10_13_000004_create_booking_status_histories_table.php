<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_status_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('changed_by_name')->nullable();
            $table->string('source', 20)->default('system');
            $table->string('change_type', 20)->default('status_change');
            $table->timestamp('changed_at');
            $table->index(['booking_id', 'changed_at']);
        });

        DB::table('bookings')->orderBy('id')->select(['id', 'status'])->chunk(500, function ($bookings): void {
            foreach ($bookings as $booking) {
                DB::table('booking_status_histories')->insert([
                    'booking_id' => $booking->id,
                    'from_status' => null,
                    'to_status' => $booking->status,
                    'changed_by_name' => 'System',
                    'source' => 'system',
                    'change_type' => 'baseline',
                    'changed_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_status_histories');
    }
};
