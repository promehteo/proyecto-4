<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Bitacora\IndexBitacoraRequest;
use App\Http\Requests\Bitacora\ShowBitacoraRequest;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\View\View;

class BitacoraController extends Controller
{
    public function index(IndexBitacoraRequest $request): View
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

        $bitacoras = Bitacora::with('usuario')
            ->search($search)
            ->byUsuario($usuarioId ? (int) $usuarioId : null)
            ->byAccion($accion)
            ->byAuditableTipo($auditableTipo)
            ->byAuditableId($auditableId ? (int) $auditableId : null)
            ->byFechaDesde($fechaDesde)
            ->byFechaHasta($fechaHasta)
            ->byIp($ip)
            ->orderByDesc('fecha_bitacora')
            ->paginate(20)
            ->withQueryString();

        $usuarios = User::orderBy('name')->get();
        $acciones = Bitacora::distinct()->pluck('accion_bitacora')->filter()->values();

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

    public function show(ShowBitacoraRequest $request, Bitacora $bitacora): View
    {
        $this->authorize('view', $bitacora);

        return view('bitacora.show', compact('bitacora'));
    }
}
