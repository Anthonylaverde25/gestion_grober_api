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
        Schema::table('extractions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignUuid('user_alias_id')->nullable()->constrained('user_aliases')->onDelete('set null');
        });

        Schema::table('line_yields', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignUuid('user_alias_id')->nullable()->constrained('user_aliases')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extractions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['user_alias_id']);
            $table->dropColumn('user_alias_id');
        });

        Schema::table('line_yields', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropForeign(['user_alias_id']);
            $table->dropColumn('user_alias_id');
        });
    }
};
