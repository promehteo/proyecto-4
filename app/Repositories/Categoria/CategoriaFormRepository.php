<?php

declare(strict_types=1);

namespace App\Repositories\Categoria;

use App\Models\Categoria;

class CategoriaFormRepository
{
    public function create(array $data): Categoria
    {
        return Categoria::create([
            'nombre_categoria' => $data['nombre_categoria'],
            'descripcion_categoria' => $data['descripcion_categoria'] ?? null,
            'id_categoria_padre_categoria' => $data['id_categoria_padre_categoria'] ?? null,
            'status' => 1,
        ]);
    }

    public function update(Categoria $categoria, array $data): Categoria
    {
        $categoria->update([
            'nombre_categoria' => $data['nombre_categoria'],
            'descripcion_categoria' => $data['descripcion_categoria'] ?? null,
            'id_categoria_padre_categoria' => $data['id_categoria_padre_categoria'] ?? null,
        ]);
        return $categoria->fresh();
    }

    public function activate(Categoria $categoria): Categoria
    {
        $categoria->update(['status' => 1]);
        return $categoria->fresh();
    }

    public function deactivate(Categoria $categoria): Categoria
    {
        $categoria->update(['status' => 2]);
        return $categoria->fresh();
    }
}
