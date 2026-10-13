<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('code', 32);
            $table->unsignedSmallInteger('floor')->default(1);
            $table->unsignedSmallInteger('capacity');
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['restaurant_id', 'code']);
            $table->index(['restaurant_id', 'floor', 'status']);
        });

        $defaults = [
            1 => ['B1' => 4, 'B2' => 4, 'B3' => 6, 'B4' => 2, 'B5' => 2, 'B6' => 4],
            2 => ['B7' => 2, 'B8' => 2, 'B9' => 4, 'B10' => 4, 'B11' => 4, 'B12' => 6],
        ];

        foreach (DB::table('restaurants')->pluck('id') as $restaurantId) {
            foreach ($defaults as $floor => $tables) {
                foreach ($tables as $position => $capacity) {
                    DB::table('restaurant_tables')->insert([
                        'restaurant_id' => $restaurantId,
                        'code' => $position,
                        'floor' => $floor,
                        'capacity' => $capacity,
                        'position' => (int) preg_replace('/\\D+/', '', $position),
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
