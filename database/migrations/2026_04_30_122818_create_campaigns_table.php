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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('codigo')->nullable();
            $table->uuid('machine_id')->index();
            $table->uuid('article_id')->index();
            $table->uuid('client_id')->index();
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['ACTIVE', 'PAUSED', 'FINISHED', 'CANCELLED'])->default('ACTIVE');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('finished_at')->nullable();
            $table->float('eficiencia_promedio')->nullable();
            $table->integer('total_yield_records')->default(0);
            $table->text('observaciones')->nullable();
            $table->json('snapshot_data')->nullable();
            $table->uuid('company_id')->index();
            $table->timestamps();

            $table->foreign('machine_id')->references('id')->on('machines');
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
