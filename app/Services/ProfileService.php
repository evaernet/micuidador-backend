<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function actualizar(User $user, array $data, $foto = null): User
    {
        if ($foto) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $foto->store('perfiles', 'public');
        }

        $user->update($data);

        return $user;
    }
}
