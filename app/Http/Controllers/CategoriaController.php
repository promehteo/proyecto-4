<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Categoria\ActivarCategoriaRequest;
use App\Http\Requests\Categoria\InactivarCategoriaRequest;
use App\Http\Requests\Categoria\StoreCategoriaRequest;
use App\Http\Requests\Categoria\UpdateCategoriaRequest;
use App\Models\Categoria;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Categoria::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $categorias = Categoria::with('padre')
            ->search($search)
            ->byStatus($status ? (int) $status : null)
            ->orderBy('nombre_categoria')
            ->paginate(15)
            ->withQueryString();

        return view('categorias.index', compact('categorias', 'search', 'status'));
    }

    public function create(): View
    {
        $this->authorize('create', Categoria::class);

        $categoriasPadre = Categoria::active()->orderBy('nombre_categoria')->get();

        return view('categorias.create', compact('categoriasPadre'));
    }

    public function store(StoreCategoriaRequest $request): RedirectResponse
    {
        $data = $request->validated();
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

    public function update(UpdateCategoriaRequest $request, Categoria $categoria): RedirectResponse
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

    public function inactivar(InactivarCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $valoresAnteriores = $categoria->toArray();
        $categoria->update(['status' => 2]);

        BitacoraService::registrar(
            auditable: $categoria,
            accion: 'inactivación de categoría',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $categoria->fresh()->toArray(),
            descripcion: "Categoría '{$categoria->nombre_categoria}' inactivada."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoria->nombre_categoria}' ha sido inactivada.");
    }

    public function activar(ActivarCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $valoresAnteriores = $categoria->toArray();
        $categoria->update(['status' => 1]);

        BitacoraService::registrar(
            auditable: $categoria,
            accion: 'activación de categoría',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $categoria->fresh()->toArray(),
            descripcion: "Categoría '{$categoria->nombre_categoria}' activada."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoria->nombre_categoria}' ha sido activada.");
    }
}
