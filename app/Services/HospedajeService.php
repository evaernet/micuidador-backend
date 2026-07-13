<?php

namespace App\Services;

use App\Models\CarakerProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class HospedajeService
{
    public function listar(?string $busqueda = null)
    {
        $query = CarakerProfile::with('user');

        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('ubicacion', 'like', "%{$busqueda}%");
            });
        }

        $hospedajes = $query->get();
        $hospedajes->each(fn (CarakerProfile $h) => $h->user->makeHidden('phone'));

        return $hospedajes;
    }

    public function ver(CarakerProfile $hospedaje): CarakerProfile
    {
        $hospedaje->load('user');
        $hospedaje->user->makeHidden('phone');

        return $hospedaje;
    }

    public function mio(User $cuidador): ?CarakerProfile
    {
        return $cuidador->cuidadorProfile;
    }

    public function actualizar(User $cuidador, array $data, $foto = null): CarakerProfile
    {
        $perfil = $cuidador->cuidadorProfile;

        if ($foto) {
            if ($perfil->foto_hospedaje) {
                Storage::disk('public')->delete($perfil->foto_hospedaje);
            }
            $data['foto_hospedaje'] = $foto->store('hospedajes', 'public');
        }

        $perfil->update($data);

        return $perfil;
    }

    public function borrarFoto(User $cuidador): CarakerProfile
    {
        $perfil = $cuidador->cuidadorProfile;

        if ($perfil->foto_hospedaje) {
            Storage::disk('public')->delete($perfil->foto_hospedaje);
            $perfil->update(['foto_hospedaje' => null]);
        }

        return $perfil;
    }
}
