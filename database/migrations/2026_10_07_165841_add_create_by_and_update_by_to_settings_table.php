<?php

use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        // These columns are already added by the earlier 162000 settings migration.
    }

    public function down(): void
    {
        // The earlier migration owns these columns and their rollback.
    }
};
