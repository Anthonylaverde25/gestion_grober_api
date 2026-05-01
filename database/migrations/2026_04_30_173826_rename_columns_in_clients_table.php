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
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('nombre_comercial', 'commercial_name');
            $table->renameColumn('razon_social', 'business_name');
            $table->renameColumn('cuit', 'tax_id');
            $table->renameColumn('contacto_tecnico', 'technical_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('commercial_name', 'nombre_comercial');
            $table->renameColumn('business_name', 'razon_social');
            $table->renameColumn('tax_id', 'cuit');
            $table->renameColumn('technical_contact', 'contacto_tecnico');
        });
    }
};
