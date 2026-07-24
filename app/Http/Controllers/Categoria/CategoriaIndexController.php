<?php

declare(strict_types=1);

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CategoriaIndexRequest;
use App\Models\Categoria;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoriaIndexController extends Controller
{
    public function index(CategoriaIndexRequest $request): View
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

    public function inactivar(CategoriaIndexRequest $request, Categoria $categoria): RedirectResponse
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

    public function activar(CategoriaIndexRequest $request, Categoria $categoria): RedirectResponse
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
