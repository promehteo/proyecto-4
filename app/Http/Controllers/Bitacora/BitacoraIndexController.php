<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bitacora;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bitacora\BitacoraIndexRequest;
use App\Models\Bitacora;
use App\Repositories\Bitacora\BitacoraListRepository;
use Illuminate\View\View;

class BitacoraIndexController extends Controller
{
    public function __construct(
        protected BitacoraListRepository $listRepository
    ) {}

    public function index(BitacoraIndexRequest $request): View
    {
        $this->authorize('viewAny', Bitacora::class);

        $search = $request->input('search');
        $usuarioId = $request->input('usuario_id');
        $accion = $request->input('accion');
        $auditableTipo = $request->input('auditable_tipo');
        $auditableId = $request->input('auditable_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $ip = $request->input('ip');

        $bitacoras = $this->listRepository->paginate([
            'search' => $search,
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'auditable_tipo' => $auditableTipo,
            'auditable_id' => $auditableId,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'ip' => $ip,
        ]);

        $usuarios = $this->listRepository->getUsersForFilter();
        $acciones = $this->listRepository->getDistinctAcciones();

        return view('bitacora.index', compact(
            'bitacoras',
            'usuarios',
            'acciones',
            'search',
            'usuarioId',
            'accion',
            'auditableTipo',
            'auditableId',
            'fechaDesde',
            'fechaHasta',
            'ip'
        ));
    }
}
