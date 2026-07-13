<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PetService
{
    public function listar(User $user)
    {
        return $user->pets;
    }

    public function crear(User $user, array $data, $foto = null): Pet
    {
        $rutaFoto = $foto ? $foto->store('pets', 'public') : null;

        return $user->pets()->create([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'nota' => $data['nota'] ?? null,
            'foto' => $rutaFoto,
        ]);
    }

    public function actualizar(User $user, Pet $pet, array $data, $foto = null): Pet
    {
        if ($pet->user_id !== $user->id) {
            throw new \Exception('No autorizado', 403);
        }

        if ($foto) {
            if ($pet->foto) {
                Storage::disk('public')->delete($pet->foto);
            }
            $data['foto'] = $foto->store('pets', 'public');
        }

        $pet->update($data);

        return $pet;
    }

    public function eliminar(User $user, Pet $pet): void
    {
        if ($pet->user_id !== $user->id) {
            throw new \Exception('No autorizado', 403);
        }

        $pet->delete();
    }
}