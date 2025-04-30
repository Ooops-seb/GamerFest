<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_juego',
        'user_id',
        'nombre_equipo',
        'miembros',
    ];

    public function juego()
    {
        return $this->belongsTo(Juego::class, 'id_juego');
    }

    public function inscripcionesGrupales()
    {
        return $this->hasMany(InscripcionGrupal::class, 'id_equipo');
    }

    public function ganadoresGrupales()
    {
        return $this->hasMany(GanadorGrupal::class, 'id_equipo');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
