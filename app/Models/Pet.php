<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','nombre', 'tipo', 'foto', 'nota'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
