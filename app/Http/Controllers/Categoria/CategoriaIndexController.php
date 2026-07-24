<?php

declare(strict_types=1);

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CategoriaIndexRequest;
use App\Models\Categoria;
use App\Repositories\Categoria\CategoriaFormRepository;
use App\Repositories\Categoria\CategoriaListRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoriaIndexController extends Controller
{
    public function __construct(
        protected CategoriaListRepository $listRepository,
        protected CategoriaFormRepository $formRepository
    ) {}

    public function index(CategoriaIndexRequest $request): View
    {
        $this->authorize('viewAny', Categoria::class);

        $search = $request->input('search');
        $status = $request->input('status');

        $categorias = $this->listRepository->paginate([
            'search' => $search,
            'status' => $status,
        ]);

        return view('categorias.index', compact('categorias', 'search', 'status'));
    }

    public function inactivar(CategoriaIndexRequest $request, Categoria $categoria): RedirectResponse
    {
        $valoresAnteriores = $categoria->toArray();
        $categoriaInactivada = $this->formRepository->deactivate($categoria);

        BitacoraService::registrar(
            auditable: $categoriaInactivada,
            accion: 'inactivación de categoría',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $categoriaInactivada->toArray(),
            descripcion: "Categoría '{$categoriaInactivada->nombre_categoria}' inactivada."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoriaInactivada->nombre_categoria}' ha sido inactivada.");
    }

    public function activar(CategoriaIndexRequest $request, Categoria $categoria): RedirectResponse
    {
        $valoresAnteriores = $categoria->toArray();
        $categoriaActivada = $this->formRepository->activate($categoria);

        BitacoraService::registrar(
            auditable: $categoriaActivada,
            accion: 'activación de categoría',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $categoriaActivada->toArray(),
            descripcion: "Categoría '{$categoriaActivada->nombre_categoria}' activada."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoriaActivada->nombre_categoria}' ha sido activada.");
    }
}
