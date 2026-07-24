<?php

declare(strict_types=1);

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CategoriaGesRequest;
use App\Models\Categoria;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoriaGesController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', Categoria::class);

        $categoriasPadre = Categoria::active()->orderBy('nombre_categoria')->get();

        return view('categorias.create', compact('categoriasPadre'));
    }

    public function store(CategoriaGesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = 1; // Todos los registros se crean activos por defecto
        $categoria = Categoria::create($data);

        BitacoraService::registrar(
            auditable: $categoria,
            accion: 'creación de categoría',
            valoresAnteriores: null,
            valoresNuevos: $categoria->toArray(),
            descripcion: "Categoría '{$categoria->nombre_categoria}' creada exitosamente."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoria->nombre_categoria}' ha sido creada correctamente.");
    }

    public function edit(Categoria $categoria): View
    {
        $this->authorize('update', $categoria);

        $categoriasPadre = Categoria::active()
            ->where('id_categoria', '!=', $categoria->id_categoria)
            ->orderBy('nombre_categoria')
            ->get();

        return view('categorias.edit', compact('categoria', 'categoriasPadre'));
    }

    public function update(CategoriaGesRequest $request, Categoria $categoria): RedirectResponse
    {
        $valoresAnteriores = $categoria->toArray();
        $data = $request->validated();

        $categoria->update($data);

        BitacoraService::registrar(
            auditable: $categoria,
            accion: 'edición de categoría',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $categoria->fresh()->toArray(),
            descripcion: "Categoría '{$categoria->nombre_categoria}' actualizada exitosamente."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoria->nombre_categoria}' ha sido actualizada correctamente.");
    }
}
