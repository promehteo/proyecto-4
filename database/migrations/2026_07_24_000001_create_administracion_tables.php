<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ============================================================
        // Tabla: rol
        // ============================================================
        Schema::create('rol', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre_rol', 100);
            $table->string('clave_rol', 120)->unique(); // Cambiado de slug_rol a clave_rol
            $table->string('descripcion_rol', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: permiso
        // ============================================================
        Schema::create('permiso', function (Blueprint $table) {
            $table->id('id_permiso');
            $table->string('nombre_permiso', 120);
            $table->string('clave_permiso', 150)->unique(); // Cambiado de slug_permiso a clave_permiso
            $table->string('modulo_permiso', 100);
            $table->string('descripcion_permiso', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: detalle_rol (Antes rol_usuario)
        // ============================================================
        Schema::create('detalle_rol', function (Blueprint $table) {
            $table->id('id_detalle_rol');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->unique(['id_usuario', 'id_rol'], 'uq_detalle_rol');

            $table->foreign('id_rol', 'fk_detalle_rol_rol')
                ->references('id_rol')
                ->on('rol')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario', 'fk_detalle_rol_user')
                ->references('id_user')
                ->on('user')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: detalle_permiso (Antes permiso_rol)
        // ============================================================
        Schema::create('detalle_permiso', function (Blueprint $table) {
            $table->id('id_detalle_permiso');
            $table->unsignedBigInteger('id_permiso');
            $table->unsignedBigInteger('id_rol');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->unique(['id_permiso', 'id_rol'], 'uq_detalle_permiso');

            $table->foreign('id_permiso', 'fk_detalle_permiso_permiso')
                ->references('id_permiso')
                ->on('permiso')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_rol', 'fk_detalle_permiso_rol')
                ->references('id_rol')
                ->on('rol')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: bitacora
        // ============================================================
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id('id_bitacora');
            $table->unsignedBigInteger('id_usuario_bitacora')->nullable();
            $table->string('accion_bitacora', 80);
            $table->string('modulo_bitacora', 190); // Cambiado de auditable_tipo_bitacora
            $table->unsignedBigInteger('registro_id_bitacora'); // Cambiado de auditable_id_bitacora
            $table->json('valores_anteriores_bitacora')->nullable();
            $table->json('valores_nuevos_bitacora')->nullable();
            $table->string('ip_bitacora', 45)->nullable();
            $table->string('navegador_bitacora', 255)->nullable(); // Cambiado de user_agent_bitacora
            $table->string('url_bitacora', 255)->nullable();
            $table->string('metodo_bitacora', 10)->nullable();
            $table->timestamp('fecha_bitacora')->useCurrent();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_usuario_bitacora', 'idx_usuario_bitacora');
            $table->index(['modulo_bitacora', 'registro_id_bitacora'], 'idx_auditable_bitacora');
            $table->index('accion_bitacora', 'idx_accion_bitacora');
            $table->index('fecha_bitacora', 'idx_fecha_bitacora');

            $table->foreign('id_usuario_bitacora', 'fk_bitacora_user')
                ->references('id_user')
                ->on('user')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });

        $this->addChecks();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'bitacora',
            'detalle_permiso',
            'detalle_rol',
            'permiso',
            'rol',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Agrega CHECK constraints solo para MySQL.
     */
    private function addChecks(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $statusTables = [
            'rol',
            'permiso',
            'detalle_rol',
            'detalle_permiso',
            'bitacora',
        ];

        foreach ($statusTables as $table) {
            $this->addCheck(
                "chk_status_{$table}",
                "ALTER TABLE `{$table}` ADD CONSTRAINT `chk_status_{$table}` CHECK (`status` IN (1, 2))"
            );
        }
    }

    /**
     * Agrega un CHECK constraint si no existe.
     */
    private function addCheck(string $name, string $sql): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if ($this->constraintExists($name)) {
            return;
        }

        DB::statement($sql);
    }

    /**
     * Verifica si un constraint ya existe en la base de datos actual.
     */
    private function constraintExists(string $name): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return false;
        }

        $result = DB::selectOne(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE()
               AND CONSTRAINT_NAME = ?
             LIMIT 1",
            [$name]
        );

        return $result !== null;
    }
};