<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_categoria_producto',
        'id_tipo_producto_producto',
        'id_marca_producto',
        'id_unidad_medida_producto',
        'codigo_interno_producto',
        'codigo_barras_producto',
        'nombre_producto',
        'descripcion_producto',
        'precio_venta_producto',
        'costo_producto',
        'stock_minimo_producto',
        'stock_maximo_producto',
        'perecedero_producto',
        'imagen_producto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
        ];
    }
}
