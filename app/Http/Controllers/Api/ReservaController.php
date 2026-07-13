<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservaRequest;
use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    protected ReservaService $reservaService;

    public function __construct(ReservaService $reservaService)
    {
        $this->reservaService = $reservaService;
    }

    public function store(ReservaRequest $request)
    {
        $reserva = $this->reservaService->crear($request->user(), $request->validated());

        return response()->json(['reserva' => $reserva], 201);
    }

    public function recibidas(Request $request)
    {
        return response()->json(['reservas' => $this->reservaService->recibidas($request->user())]);
    }

    public function mias(Request $request)
    {
        return response()->json(['reservas' => $this->reservaService->mias($request->user())]);
    }

    public function aceptar(Request $request, Reserva $reserva)
    {
        try {
            $reserva = $this->reservaService->aceptar($request->user(), $reserva);

            return response()->json(['reserva' => $reserva]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function rechazar(Request $request, Reserva $reserva)
    {
        try {
            $reserva = $this->reservaService->rechazar($request->user(), $reserva);

            return response()->json(['reserva' => $reserva]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function cancelar(Request $request, Reserva $reserva)
    {
        try {
            $reserva = $this->reservaService->cancelar($request->user(), $reserva);

            return response()->json(['reserva' => $reserva]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function destroy(Request $request, Reserva $reserva)
    {
        try {
            $this->reservaService->eliminar($request->user(), $reserva);

            return response()->json(['message' => 'Reserva eliminada']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
