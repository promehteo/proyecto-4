<?php

declare(strict_types=1);

namespace App\Http\Controllers\Bitacora;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bitacora\BitacoraShowRequest;
use App\Models\Bitacora;
use Illuminate\View\View;

class BitacoraShowController extends Controller
{
    public function show(BitacoraShowRequest $request, Bitacora $bitacora): View
    {
        $this->authorize('view', $bitacora);

        return view('bitacora.show', compact('bitacora'));
    }
}
