<?php

declare(strict_types=1);

namespace App\Repositories\Categoria;

use App\Models\Categoria;

class CategoriaFormRepository
{
    public function create(array $data): Categoria
    {
        return Categoria::create($data);
    }

    public function update(Categoria $categoria, array $data): Categoria
    {
        $categoria->update($data);
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
