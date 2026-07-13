<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HospedajeRequest;
use App\Models\CarakerProfile;
use App\Services\HospedajeService;
use Illuminate\Http\Request;

class HospedajeController extends Controller
{
    protected HospedajeService $hospedajeService;

    public function __construct(HospedajeService $hospedajeService)
    {
        $this->hospedajeService = $hospedajeService;
    }

    public function index(Request $request)
    {
        $hospedajes = $this->hospedajeService->listar($request->query('q'));

        return response()->json(['hospedajes' => $hospedajes]);
    }

    public function show(CarakerProfile $hospedaje)
    {
        return response()->json(['hospedaje' => $this->hospedajeService->ver($hospedaje)]);
    }

    public function mio(Request $request)
    {
        return response()->json(['hospedaje' => $this->hospedajeService->mio($request->user())]);
    }

    public function update(HospedajeRequest $request)
    {
        $hospedaje = $this->hospedajeService->actualizar(
            $request->user(),
            $request->validated(),
            $request->file('foto_hospedaje')
        );

        return response()->json(['hospedaje' => $hospedaje]);
    }

    public function borrarFoto(Request $request)
    {
        $hospedaje = $this->hospedajeService->borrarFoto($request->user());

        return response()->json(['hospedaje' => $hospedaje]);
    }
}
