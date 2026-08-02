<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bitacora;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use App\Repositories\Bitacora\BitacoraDetailRepository;
use Illuminate\View\View;

class BitacoraShowController extends Controller
{
    public function __construct(
        protected BitacoraDetailRepository $detailRepository
    ) {}

    public function show(Bitacora $bitacora): View
    {
        $this->authorize('view', $bitacora);

        $bitacoraDetail = $this->detailRepository->findDetailById($bitacora->id_bitacora) ?? $bitacora;

        return view('bitacora.show', ['bitacora' => $bitacoraDetail]);
    }
}
