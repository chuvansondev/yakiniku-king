<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('menu_categories')->restrictOnDelete();
        });

        Schema::table('combo_items', function (Blueprint $table): void {
            $table->dropForeign(['menu_item_id']);
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('combo_items', function (Blueprint $table): void {
            $table->dropForeign(['menu_item_id']);
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->cascadeOnDelete();
        });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('menu_categories')->cascadeOnDelete();
        });
    }
};
