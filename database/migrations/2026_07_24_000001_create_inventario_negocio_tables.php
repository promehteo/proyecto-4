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
        $this->ensureUsersStatus();

        // ============================================================
        // Tabla: rol
        // ============================================================
        Schema::create('rol', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre_rol', 100);
            $table->string('slug_rol', 120)->unique();
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
            $table->string('slug_permiso', 150)->unique();
            $table->string('modulo_permiso', 100);
            $table->string('descripcion_permiso', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: rol_usuario
        // ============================================================
        Schema::create('rol_usuario', function (Blueprint $table) {
            $table->id('id_rol_usuario');
            $table->unsignedBigInteger('id_rol_rol_usuario');
            $table->unsignedBigInteger('id_usuario_rol_usuario');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->unique(
                ['id_rol_rol_usuario', 'id_usuario_rol_usuario'],
                'uq_rol_usuario'
            );

            $table->foreign('id_rol_rol_usuario', 'fk_rol_usuario_rol')
                ->references('id_rol')
                ->on('rol')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario_rol_usuario', 'fk_rol_usuario_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: permiso_rol
        // ============================================================
        Schema::create('permiso_rol', function (Blueprint $table) {
            $table->id('id_permiso_rol');
            $table->unsignedBigInteger('id_permiso_permiso_rol');
            $table->unsignedBigInteger('id_rol_permiso_rol');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->unique(
                ['id_permiso_permiso_rol', 'id_rol_permiso_rol'],
                'uq_permiso_rol'
            );

            $table->foreign('id_permiso_permiso_rol', 'fk_permiso_rol_permiso')
                ->references('id_permiso')
                ->on('permiso')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_rol_permiso_rol', 'fk_permiso_rol_rol')
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
            $table->string('usuario_snapshot_bitacora', 190)->nullable();
            $table->string('accion_bitacora', 80);
            $table->string('auditable_tipo_bitacora', 190);
            $table->unsignedBigInteger('auditable_id_bitacora');
            $table->json('valores_anteriores_bitacora')->nullable();
            $table->json('valores_nuevos_bitacora')->nullable();
            $table->string('ip_bitacora', 45)->nullable();
            $table->string('user_agent_bitacora')->nullable();
            $table->string('url_bitacora')->nullable();
            $table->string('metodo_bitacora', 10)->nullable();
            $table->string('descripcion_bitacora')->nullable();
            $table->timestamp('fecha_bitacora')->useCurrent();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_usuario_bitacora', 'idx_usuario_bitacora');
            $table->index(
                ['auditable_tipo_bitacora', 'auditable_id_bitacora'],
                'idx_auditable_bitacora'
            );
            $table->index('accion_bitacora', 'idx_accion_bitacora');
            $table->index('fecha_bitacora', 'idx_fecha_bitacora');

            $table->foreign('id_usuario_bitacora', 'fk_bitacora_user')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: categoria
        // ============================================================
        Schema::create('categoria', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->unsignedBigInteger('id_categoria_padre_categoria')->nullable();
            $table->string('nombre_categoria', 120);
            $table->string('descripcion_categoria', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_categoria_padre_categoria', 'idx_categoria_padre');

            $table->foreign('id_categoria_padre_categoria', 'fk_categoria_padre')
                ->references('id_categoria')
                ->on('categoria')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: tipo_producto
        // ============================================================
        Schema::create('tipo_producto', function (Blueprint $table) {
            $table->id('id_tipo_producto');
            $table->string('nombre_tipo_producto', 100)->unique();
            $table->string('descripcion_tipo_producto', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: marca
        // ============================================================
        Schema::create('marca', function (Blueprint $table) {
            $table->id('id_marca');
            $table->string('nombre_marca', 120)->unique();
            $table->string('descripcion_marca', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: unidad_medida
        // ============================================================
        Schema::create('unidad_medida', function (Blueprint $table) {
            $table->id('id_unidad_medida');
            $table->string('nombre_unidad_medida', 80)->unique();
            $table->string('simbolo_unidad_medida', 15)->unique();
            $table->string('descripcion_unidad_medida', 255)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: producto
        // ============================================================
        Schema::create('producto', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('id_categoria_producto');
            $table->unsignedBigInteger('id_tipo_producto_producto');
            $table->unsignedBigInteger('id_marca_producto')->nullable();
            $table->unsignedBigInteger('id_unidad_medida_producto');
            $table->string('codigo_interno_producto', 80)->unique();
            $table->string('codigo_barras_producto', 120)->nullable()->unique();
            $table->string('nombre_producto', 190)->index();
            $table->text('descripcion_producto')->nullable();
            $table->decimal('precio_venta_producto', 16, 2)->default(0);
            $table->decimal('costo_producto', 16, 2)->default(0);
            $table->decimal('stock_minimo_producto', 12, 3)->nullable();
            $table->decimal('stock_maximo_producto', 12, 3)->nullable();
            $table->boolean('perecedero_producto')->default(false);
            $table->string('imagen_producto')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_categoria_producto', 'idx_categoria_producto');
            $table->index('id_tipo_producto_producto', 'idx_tipo_producto_producto');
            $table->index('id_marca_producto', 'idx_marca_producto');
            $table->index('id_unidad_medida_producto', 'idx_unidad_medida_producto');

            $table->foreign('id_categoria_producto', 'fk_producto_categoria')
                ->references('id_categoria')
                ->on('categoria')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_tipo_producto_producto', 'fk_producto_tipo_producto')
                ->references('id_tipo_producto')
                ->on('tipo_producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_marca_producto', 'fk_producto_marca')
                ->references('id_marca')
                ->on('marca')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_unidad_medida_producto', 'fk_producto_unidad_medida')
                ->references('id_unidad_medida')
                ->on('unidad_medida')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: proveedor
        // ============================================================
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('nombre_proveedor', 190);
            $table->string('documento_fiscal_proveedor', 50)->nullable()->unique();
            $table->string('telefono_proveedor', 40)->nullable();
            $table->string('correo_proveedor', 190)->nullable();
            $table->string('direccion_proveedor', 255)->nullable();
            $table->text('observacion_proveedor')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: cliente
        // ============================================================
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre_cliente', 190);
            $table->string('documento_fiscal_cliente', 50)->nullable()->unique();
            $table->string('telefono_cliente', 40)->nullable();
            $table->string('correo_cliente', 190)->nullable();
            $table->string('direccion_cliente', 255)->nullable();
            $table->text('observacion_cliente')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');
        });

        // ============================================================
        // Tabla: compra
        // ============================================================
        Schema::create('compra', function (Blueprint $table) {
            $table->id('id_compra');
            $table->unsignedBigInteger('id_proveedor_compra');
            $table->unsignedBigInteger('id_usuario_compra');
            $table->string('numero_documento_compra', 80)->nullable();
            $table->dateTime('fecha_compra');
            $table->decimal('subtotal_compra', 16, 2)->default(0);
            $table->decimal('impuesto_compra', 16, 2)->default(0);
            $table->decimal('descuento_compra', 16, 2)->default(0);
            $table->decimal('total_compra', 16, 2)->default(0);
            $table->string('estado_compra', 40)
                ->default('borrador')
                ->comment('borrador, confirmada, recibida, anulada');
            $table->text('observacion_compra')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_proveedor_compra', 'idx_proveedor_compra');
            $table->index('id_usuario_compra', 'idx_usuario_compra');
            $table->index('fecha_compra', 'idx_fecha_compra');
            $table->index('estado_compra', 'idx_estado_compra');

            $table->foreign('id_proveedor_compra', 'fk_compra_proveedor')
                ->references('id_proveedor')
                ->on('proveedor')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario_compra', 'fk_compra_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: detalle_compra
        // ============================================================
        Schema::create('detalle_compra', function (Blueprint $table) {
            $table->id('id_detalle_compra');
            $table->unsignedBigInteger('id_compra_detalle_compra');
            $table->unsignedBigInteger('id_producto_detalle_compra');
            $table->decimal('cantidad_detalle_compra', 12, 3);
            $table->decimal('costo_unitario_detalle_compra', 16, 2)->default(0);
            $table->decimal('subtotal_detalle_compra', 16, 2)->default(0);
            $table->decimal('impuesto_detalle_compra', 16, 2)->default(0);
            $table->decimal('total_detalle_compra', 16, 2)->default(0);
            $table->date('fecha_vencimiento_detalle_compra')->nullable();
            $table->string('lote_detalle_compra', 100)->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_compra_detalle_compra', 'idx_compra_detalle_compra');
            $table->index('id_producto_detalle_compra', 'idx_producto_detalle_compra');

            $table->foreign('id_compra_detalle_compra', 'fk_detalle_compra_compra')
                ->references('id_compra')
                ->on('compra')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_producto_detalle_compra', 'fk_detalle_compra_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: venta
        // ============================================================
        Schema::create('venta', function (Blueprint $table) {
            $table->id('id_venta');
            $table->unsignedBigInteger('id_cliente_venta');
            $table->unsignedBigInteger('id_usuario_venta');
            $table->string('numero_documento_venta', 80)->nullable();
            $table->dateTime('fecha_venta');
            $table->decimal('subtotal_venta', 16, 2)->default(0);
            $table->decimal('impuesto_venta', 16, 2)->default(0);
            $table->decimal('descuento_venta', 16, 2)->default(0);
            $table->decimal('total_venta', 16, 2)->default(0);
            $table->string('metodo_pago_venta', 60)->nullable();
            $table->string('estado_venta', 40)
                ->default('borrador')
                ->comment('borrador, confirmada, pagada, anulada, devuelta');
            $table->text('observacion_venta')->nullable();
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_cliente_venta', 'idx_cliente_venta');
            $table->index('id_usuario_venta', 'idx_usuario_venta');
            $table->index('fecha_venta', 'idx_fecha_venta');
            $table->index('estado_venta', 'idx_estado_venta');

            $table->foreign('id_cliente_venta', 'fk_venta_cliente')
                ->references('id_cliente')
                ->on('cliente')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario_venta', 'fk_venta_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: detalle_venta
        // ============================================================
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id('id_detalle_venta');
            $table->unsignedBigInteger('id_venta_detalle_venta');
            $table->unsignedBigInteger('id_producto_detalle_venta');
            $table->decimal('cantidad_detalle_venta', 12, 3);
            $table->decimal('precio_unitario_detalle_venta', 16, 2)->default(0);
            $table->decimal('descuento_detalle_venta', 16, 2)->default(0);
            $table->decimal('subtotal_detalle_venta', 16, 2)->default(0);
            $table->decimal('impuesto_detalle_venta', 16, 2)->default(0);
            $table->decimal('total_detalle_venta', 16, 2)->default(0);
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_venta_detalle_venta', 'idx_venta_detalle_venta');
            $table->index('id_producto_detalle_venta', 'idx_producto_detalle_venta');

            $table->foreign('id_venta_detalle_venta', 'fk_detalle_venta_venta')
                ->references('id_venta')
                ->on('venta')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_producto_detalle_venta', 'fk_detalle_venta_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: stock
        // ============================================================
        Schema::create('stock', function (Blueprint $table) {
            $table->id('id_stock');
            $table->unsignedBigInteger('id_producto_stock')->unique();
            $table->decimal('cantidad_stock', 12, 3)->default(0);
            $table->decimal('cantidad_reservada_stock', 12, 3)->default(0);
            $table->decimal('cantidad_disponible_stock', 12, 3)
                ->storedAs('cantidad_stock - cantidad_reservada_stock');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->foreign('id_producto_stock', 'fk_stock_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: movimiento_inventario
        // ============================================================
        Schema::create('movimiento_inventario', function (Blueprint $table) {
            $table->id('id_movimiento_inventario');
            $table->unsignedBigInteger('id_producto_movimiento_inventario');
            $table->unsignedBigInteger('id_usuario_movimiento_inventario');
            $table->string('tipo_movimiento_inventario', 50)
                ->comment('entrada_compra, salida_venta, devolucion_compra, devolucion_venta, ajuste_positivo, ajuste_negativo, merma, baja, consumo_interno, inventario_inicial, anulacion');
            $table->decimal('cantidad_movimiento_inventario', 12, 3);
            $table->decimal('stock_anterior_movimiento_inventario', 12, 3)->default(0);
            $table->decimal('stock_resultante_movimiento_inventario', 12, 3)->default(0);
            $table->string('documento_tipo_movimiento_inventario', 80)->nullable();
            $table->unsignedBigInteger('documento_id_movimiento_inventario')->nullable();
            $table->string('motivo_movimiento_inventario');
            $table->text('observacion_movimiento_inventario')->nullable();
            $table->dateTime('fecha_movimiento_inventario');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_producto_movimiento_inventario', 'idx_producto_movimiento');
            $table->index('id_usuario_movimiento_inventario', 'idx_usuario_movimiento');
            $table->index('tipo_movimiento_inventario', 'idx_tipo_movimiento');
            $table->index('fecha_movimiento_inventario', 'idx_fecha_movimiento');
            $table->index(
                ['documento_tipo_movimiento_inventario', 'documento_id_movimiento_inventario'],
                'idx_documento_movimiento'
            );

            $table->foreign('id_producto_movimiento_inventario', 'fk_movimiento_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario_movimiento_inventario', 'fk_movimiento_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: ajuste_inventario
        // ============================================================
        Schema::create('ajuste_inventario', function (Blueprint $table) {
            $table->id('id_ajuste_inventario');
            $table->unsignedBigInteger('id_producto_ajuste_inventario');
            $table->unsignedBigInteger('id_usuario_ajuste_inventario');
            $table->string('tipo_ajuste_inventario', 30)
                ->comment('positivo, negativo');
            $table->decimal('cantidad_ajuste_inventario', 12, 3);
            $table->string('motivo_ajuste_inventario');
            $table->text('observacion_ajuste_inventario')->nullable();
            $table->dateTime('fecha_ajuste_inventario');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_producto_ajuste_inventario', 'idx_producto_ajuste');
            $table->index('id_usuario_ajuste_inventario', 'idx_usuario_ajuste');
            $table->index('fecha_ajuste_inventario', 'idx_fecha_ajuste');

            $table->foreign('id_producto_ajuste_inventario', 'fk_ajuste_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario_ajuste_inventario', 'fk_ajuste_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });

        // ============================================================
        // Tabla: merma
        // ============================================================
        Schema::create('merma', function (Blueprint $table) {
            $table->id('id_merma');
            $table->unsignedBigInteger('id_producto_merma');
            $table->unsignedBigInteger('id_usuario_merma');
            $table->decimal('cantidad_merma', 12, 3);
            $table->string('motivo_merma');
            $table->text('observacion_merma')->nullable();
            $table->string('evidencia_merma')->nullable();
            $table->dateTime('fecha_merma');
            $table->unsignedTinyInteger('status')
                ->default(1)
                ->index()
                ->comment('1=activo, 2=inactivo');

            $table->index('id_producto_merma', 'idx_producto_merma');
            $table->index('id_usuario_merma', 'idx_usuario_merma');
            $table->index('fecha_merma', 'idx_fecha_merma');

            $table->foreign('id_producto_merma', 'fk_merma_producto')
                ->references('id_producto')
                ->on('producto')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_usuario_merma', 'fk_merma_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
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
            'merma',
            'ajuste_inventario',
            'movimiento_inventario',
            'stock',
            'detalle_venta',
            'venta',
            'detalle_compra',
            'compra',
            'cliente',
            'proveedor',
            'producto',
            'unidad_medida',
            'marca',
            'tipo_producto',
            'categoria',
            'bitacora',
            'permiso_rol',
            'rol_usuario',
            'permiso',
            'rol',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'status')) {
            if (DB::getDriverName() === 'mysql' && $this->constraintExists('chk_status_users')) {
                DB::statement('ALTER TABLE `users` DROP CHECK `chk_status_users`');
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Garantiza que la tabla users tenga el campo status.
     */
    private function ensureUsersStatus(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->unsignedTinyInteger('status')
                    ->default(1)
                    ->index()
                    ->comment('1=activo, 2=inactivo');
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('status')
                    ->default(1)
                    ->after('remember_token')
                    ->index()
                    ->comment('1=activo, 2=inactivo');
            });
        }

        if (DB::getDriverName() === 'mysql' && Schema::hasTable('users')) {
            $this->addCheck(
                'chk_status_users',
                "ALTER TABLE `users` ADD CONSTRAINT `chk_status_users` CHECK (`status` IN (1, 2))"
            );
        }
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
            'rol_usuario',
            'permiso_rol',
            'bitacora',
            'categoria',
            'tipo_producto',
            'marca',
            'unidad_medida',
            'producto',
            'proveedor',
            'cliente',
            'compra',
            'detalle_compra',
            'venta',
            'detalle_venta',
            'stock',
            'movimiento_inventario',
            'ajuste_inventario',
            'merma',
        ];

        foreach ($statusTables as $table) {
            $this->addCheck(
                "chk_status_{$table}",
                "ALTER TABLE `{$table}` ADD CONSTRAINT `chk_status_{$table}` CHECK (`status` IN (1, 2))"
            );
        }

        $checks = [
            'chk_producto_perecedero' => "ALTER TABLE `producto` ADD CONSTRAINT `chk_producto_perecedero` CHECK (`perecedero_producto` IN (0, 1))",
            'chk_producto_precio_venta' => "ALTER TABLE `producto` ADD CONSTRAINT `chk_producto_precio_venta` CHECK (`precio_venta_producto` >= 0)",
            'chk_producto_costo' => "ALTER TABLE `producto` ADD CONSTRAINT `chk_producto_costo` CHECK (`costo_producto` >= 0)",
            'chk_producto_stock_min' => "ALTER TABLE `producto` ADD CONSTRAINT `chk_producto_stock_min` CHECK (`stock_minimo_producto` IS NULL OR `stock_minimo_producto` >= 0)",
            'chk_producto_stock_max' => "ALTER TABLE `producto` ADD CONSTRAINT `chk_producto_stock_max` CHECK (`stock_maximo_producto` IS NULL OR `stock_maximo_producto` >= 0)",

            'chk_compra_estado' => "ALTER TABLE `compra` ADD CONSTRAINT `chk_compra_estado` CHECK (`estado_compra` IN ('borrador', 'confirmada', 'recibida', 'anulada'))",
            'chk_compra_subtotal' => "ALTER TABLE `compra` ADD CONSTRAINT `chk_compra_subtotal` CHECK (`subtotal_compra` >= 0)",
            'chk_compra_impuesto' => "ALTER TABLE `compra` ADD CONSTRAINT `chk_compra_impuesto` CHECK (`impuesto_compra` >= 0)",
            'chk_compra_descuento' => "ALTER TABLE `compra` ADD CONSTRAINT `chk_compra_descuento` CHECK (`descuento_compra` >= 0)",
            'chk_compra_total' => "ALTER TABLE `compra` ADD CONSTRAINT `chk_compra_total` CHECK (`total_compra` >= 0)",
            'chk_compra_descuento_logico' => "ALTER TABLE `compra` ADD CONSTRAINT `chk_compra_descuento_logico` CHECK (`descuento_compra` <= (`subtotal_compra` + `impuesto_compra`))",

            'chk_detalle_compra_cantidad' => "ALTER TABLE `detalle_compra` ADD CONSTRAINT `chk_detalle_compra_cantidad` CHECK (`cantidad_detalle_compra` > 0)",
            'chk_detalle_compra_costo' => "ALTER TABLE `detalle_compra` ADD CONSTRAINT `chk_detalle_compra_costo` CHECK (`costo_unitario_detalle_compra` >= 0)",
            'chk_detalle_compra_subtotal' => "ALTER TABLE `detalle_compra` ADD CONSTRAINT `chk_detalle_compra_subtotal` CHECK (`subtotal_detalle_compra` >= 0)",
            'chk_detalle_compra_impuesto' => "ALTER TABLE `detalle_compra` ADD CONSTRAINT `chk_detalle_compra_impuesto` CHECK (`impuesto_detalle_compra` >= 0)",
            'chk_detalle_compra_total' => "ALTER TABLE `detalle_compra` ADD CONSTRAINT `chk_detalle_compra_total` CHECK (`total_detalle_compra` >= 0)",

            'chk_venta_estado' => "ALTER TABLE `venta` ADD CONSTRAINT `chk_venta_estado` CHECK (`estado_venta` IN ('borrador', 'confirmada', 'pagada', 'anulada', 'devuelta'))",
            'chk_venta_subtotal' => "ALTER TABLE `venta` ADD CONSTRAINT `chk_venta_subtotal` CHECK (`subtotal_venta` >= 0)",
            'chk_venta_impuesto' => "ALTER TABLE `venta` ADD CONSTRAINT `chk_venta_impuesto` CHECK (`impuesto_venta` >= 0)",
            'chk_venta_descuento' => "ALTER TABLE `venta` ADD CONSTRAINT `chk_venta_descuento` CHECK (`descuento_venta` >= 0)",
            'chk_venta_total' => "ALTER TABLE `venta` ADD CONSTRAINT `chk_venta_total` CHECK (`total_venta` >= 0)",
            'chk_venta_descuento_logico' => "ALTER TABLE `venta` ADD CONSTRAINT `chk_venta_descuento_logico` CHECK (`descuento_venta` <= (`subtotal_venta` + `impuesto_venta`))",

            'chk_detalle_venta_cantidad' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_cantidad` CHECK (`cantidad_detalle_venta` > 0)",
            'chk_detalle_venta_precio' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_precio` CHECK (`precio_unitario_detalle_venta` >= 0)",
            'chk_detalle_venta_descuento' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_descuento` CHECK (`descuento_detalle_venta` >= 0)",
            'chk_detalle_venta_subtotal' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_subtotal` CHECK (`subtotal_detalle_venta` >= 0)",
            'chk_detalle_venta_impuesto' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_impuesto` CHECK (`impuesto_detalle_venta` >= 0)",
            'chk_detalle_venta_total' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_total` CHECK (`total_detalle_venta` >= 0)",
            'chk_detalle_venta_descuento_logico' => "ALTER TABLE `detalle_venta` ADD CONSTRAINT `chk_detalle_venta_descuento_logico` CHECK (`descuento_detalle_venta` <= (`precio_unitario_detalle_venta` * `cantidad_detalle_venta`))",

            'chk_stock_cantidad' => "ALTER TABLE `stock` ADD CONSTRAINT `chk_stock_cantidad` CHECK (`cantidad_stock` >= 0)",
            'chk_stock_reservada' => "ALTER TABLE `stock` ADD CONSTRAINT `chk_stock_reservada` CHECK (`cantidad_reservada_stock` >= 0)",
            'chk_stock_disponible' => "ALTER TABLE `stock` ADD CONSTRAINT `chk_stock_disponible` CHECK (`cantidad_disponible_stock` >= 0)",

            'chk_movimiento_tipo' => "ALTER TABLE `movimiento_inventario` ADD CONSTRAINT `chk_movimiento_tipo` CHECK (`tipo_movimiento_inventario` IN ('entrada_compra', 'salida_venta', 'devolucion_compra', 'devolucion_venta', 'ajuste_positivo', 'ajuste_negativo', 'merma', 'baja', 'consumo_interno', 'inventario_inicial', 'anulacion'))",
            'chk_movimiento_cantidad' => "ALTER TABLE `movimiento_inventario` ADD CONSTRAINT `chk_movimiento_cantidad` CHECK (`cantidad_movimiento_inventario` > 0)",
            'chk_movimiento_stock_anterior' => "ALTER TABLE `movimiento_inventario` ADD CONSTRAINT `chk_movimiento_stock_anterior` CHECK (`stock_anterior_movimiento_inventario` >= 0)",
            'chk_movimiento_stock_resultante' => "ALTER TABLE `movimiento_inventario` ADD CONSTRAINT `chk_movimiento_stock_resultante` CHECK (`stock_resultante_movimiento_inventario` >= 0)",
            'chk_movimiento_documento' => "ALTER TABLE `movimiento_inventario` ADD CONSTRAINT `chk_movimiento_documento` CHECK ((`documento_tipo_movimiento_inventario` IS NULL AND `documento_id_movimiento_inventario` IS NULL) OR (`documento_tipo_movimiento_inventario` IS NOT NULL AND `documento_id_movimiento_inventario` IS NOT NULL))",

            'chk_ajuste_tipo' => "ALTER TABLE `ajuste_inventario` ADD CONSTRAINT `chk_ajuste_tipo` CHECK (`tipo_ajuste_inventario` IN ('positivo', 'negativo'))",
            'chk_ajuste_cantidad' => "ALTER TABLE `ajuste_inventario` ADD CONSTRAINT `chk_ajuste_cantidad` CHECK (`cantidad_ajuste_inventario` > 0)",

            'chk_merma_cantidad' => "ALTER TABLE `merma` ADD CONSTRAINT `chk_merma_cantidad` CHECK (`cantidad_merma` > 0)",
        ];

        foreach ($checks as $name => $sql) {
            $this->addCheck($name, $sql);
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