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
        Schema::create('detalles_factura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_factura')->constrained('facturas')->onDelete('cascade');
            $table->foreignId('id_menu')->nullable()->constrained('menus')->onDelete('set null');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->text('nombre_menu');
            $table->decimal('precio_menu', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_factura');
    }
};
