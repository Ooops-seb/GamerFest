<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GanadorIndividual extends Model
{
    use HasFactory;

    protected $table = 'ganadores_individuales';

    protected $fillable = [
        'id_juego',
        'id_participante',
        'posicion',
    ];

    public function juego()
    {
        return $this->belongsTo(Juego::class, 'id_juego');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
