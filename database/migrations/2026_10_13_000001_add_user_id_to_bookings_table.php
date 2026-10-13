<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->foreignId('user_id')->nullable()->after('restaurant_id')->constrained()->nullOnDelete();
            $table->index(['user_id', 'booking_date']);
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropIndex(['user_id', 'booking_date']);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
