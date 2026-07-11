<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Laravel\Sanctum\HasApiTokens;   //Importo para poder usar sanctum tokens
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens,HasRoles;   //Aca tambien agrego HasApiTokens le da a users capacidad de crear tokens $user->createToken()

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [     // protected $fillable define q campos de se pueden agregar masivamennte, laravel solo permitira los campos fillable
        'name',                 //Quiere decir que estos campos se pueden llenar con create()
        'last_name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'birth_date',
        'status',
        'otp_code',
        'otp_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [   // oculta campos sencibless en json, con hidden no aparecen
        'password',
        'remember_token',
        'otp_code'
    ];


    protected function casts(): array     // convierte tipos automaticamente
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',               //linea importante, laravel hashea passwords,
            'birth_date'=>'date'
        ];
    }

    public function cuidadorProfile()
{
    return $this->hasOne(CarakerProfile::class);
}

public function pets()
{
    return $this->hasMany(Pet::class);
}
}
