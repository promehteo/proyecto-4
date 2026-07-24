<?php

declare(strict_types=1);

namespace App\Http\Controllers\Categoria;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CategoriaGesRequest;
use App\Models\Categoria;
use App\Repositories\Categoria\CategoriaFormRepository;
use App\Repositories\Categoria\CategoriaListRepository;
use App\Services\BitacoraService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoriaFormController extends Controller
{
    public function __construct(
        protected CategoriaListRepository $listRepository,
        protected CategoriaFormRepository $formRepository
    ) {}

    public function create(): View
    {
        $this->authorize('create', Categoria::class);

        $categoriasPadre = $this->listRepository->getActiveForSelect();

        return view('categorias.create', compact('categoriasPadre'));
    }

    public function store(CategoriaGesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $categoria = $this->formRepository->create($data);

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

        $categoriasPadre = $this->listRepository->getActiveForSelect($categoria->id_categoria);

        return view('categorias.edit', compact('categoria', 'categoriasPadre'));
    }

    public function update(CategoriaGesRequest $request, Categoria $categoria): RedirectResponse
    {
        $valoresAnteriores = $categoria->toArray();
        $data = $request->validated();

        $categoriaActualizada = $this->formRepository->update($categoria, $data);

        BitacoraService::registrar(
            auditable: $categoriaActualizada,
            accion: 'edición de categoría',
            valoresAnteriores: $valoresAnteriores,
            valoresNuevos: $categoriaActualizada->toArray(),
            descripcion: "Categoría '{$categoriaActualizada->nombre_categoria}' actualizada exitosamente."
        );

        return redirect()->route('categorias.index')
            ->with('success', "La categoría '{$categoriaActualizada->nombre_categoria}' ha sido actualizada correctamente.");
    }
}
