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
        Schema::table('compras', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('envio', 10, 2)->default(0);
            $table->string('metodo_pago')->nullable();
            $table->string('direccion_envio')->nullable();
            $table->string('estado')->default('completada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'impuesto',
                'envio',
                'metodo_pago',
                'direccion_envio',
                'estado',
            ]);
        });
    }
};
