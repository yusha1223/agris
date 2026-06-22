<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop existing unique index first, then re-add as nullable unique
            $table->dropUnique(['noTelp']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('noTelp', 20)->nullable()->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('noTelp', 20)->nullable(false)->change();
        });
    }
};
