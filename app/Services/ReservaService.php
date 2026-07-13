<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\User;

class ReservaService
{
    private const CON_PRECIO = ['pets', 'dueno', 'cuidador.cuidadorProfile'];

    public function crear(User $dueno, array $data): Reserva
    {
        $reserva = Reserva::create([
            'dueno_id' => $dueno->id,
            'cuidador_id' => $data['cuidador_id'],
            'estado' => 'pendiente',
            'fecha_llegada' => $data['fecha_llegada'],
            'hora_llegada' => $data['hora_llegada'],
            'fecha_retiro' => $data['fecha_retiro'],
            'hora_retiro' => $data['hora_retiro'],
        ]);

        $reserva->pets()->attach($data['pets']);

        return $reserva->load('pets', 'cuidador.cuidadorProfile');
    }

    public function recibidas(User $cuidador)
    {
        return Reserva::where('cuidador_id', $cuidador->id)
            ->with(self::CON_PRECIO)
            ->latest()
            ->get()
            ->each(fn (Reserva $reserva) => $this->ajustarVisibilidadTelefono($reserva, 'dueno'));
    }

    public function mias(User $dueno)
    {
        return Reserva::where('dueno_id', $dueno->id)
            ->with(self::CON_PRECIO)
            ->latest()
            ->get()
            ->each(fn (Reserva $reserva) => $this->ajustarVisibilidadTelefono($reserva, 'cuidador'));
    }

    public function aceptar(User $cuidador, Reserva $reserva): Reserva
    {
        $this->verificarPerteneceCuidador($cuidador, $reserva);
        $reserva->update(['estado' => 'aceptada']);

        return $this->ajustarVisibilidadTelefono($reserva->load(self::CON_PRECIO), 'dueno');
    }

    public function rechazar(User $cuidador, Reserva $reserva): Reserva
    {
        $this->verificarPerteneceCuidador($cuidador, $reserva);
        $reserva->update(['estado' => 'rechazada']);

        return $this->ajustarVisibilidadTelefono($reserva->load(self::CON_PRECIO), 'dueno');
    }

    // Cancela el dueño o el cuidador — cualquiera de las dos partes de la reserva.
    public function cancelar(User $user, Reserva $reserva): Reserva
    {
        if ($reserva->dueno_id !== $user->id && $reserva->cuidador_id !== $user->id) {
            throw new \Exception('No autorizado', 403);
        }

        if (!in_array($reserva->estado, ['pendiente', 'aceptada'])) {
            throw new \Exception('Esta reserva no se puede cancelar', 422);
        }

        $reserva->update(['estado' => 'cancelada']);

        $relacion = $reserva->dueno_id === $user->id ? 'cuidador' : 'dueno';

        return $this->ajustarVisibilidadTelefono($reserva->load(self::CON_PRECIO), $relacion);
    }

    // Solo el dueño puede borrar del historial, y solo si ya no está activa.
    public function eliminar(User $dueno, Reserva $reserva): void
    {
        if ($reserva->dueno_id !== $dueno->id) {
            throw new \Exception('No autorizado', 403);
        }

        $finalizada = $reserva->estado === 'aceptada' && $reserva->fecha_retiro < now()->toDateString();
        $eliminable = in_array($reserva->estado, ['rechazada', 'cancelada']) || $finalizada;

        if (!$eliminable) {
            throw new \Exception('Esta reserva todavía está activa', 422);
        }

        $reserva->delete();
    }

    private function verificarPerteneceCuidador(User $cuidador, Reserva $reserva): void
    {
        if ($reserva->cuidador_id !== $cuidador->id) {
            throw new \Exception('No autorizado', 403);
        }
    }

    // El teléfono de la otra parte solo se muestra cuando la reserva está aceptada.
    private function ajustarVisibilidadTelefono(Reserva $reserva, string $relacion): Reserva
    {
        if ($reserva->estado === 'aceptada') {
            $reserva->{$relacion}->makeVisible('phone');
        } else {
            $reserva->{$relacion}->makeHidden('phone');
        }

        return $reserva;
    }
}
