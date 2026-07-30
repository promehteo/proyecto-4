<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esta migración ha sido vaciada debido a que todas las tablas permitidas
        // ya están definidas en otras migraciones.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay tablas que revertir en esta migración.
    }
};