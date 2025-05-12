<?php

namespace Database\Seeders;

use App\Models\Juego;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $juegos = [
            [
                'nombre' => 'Fornite',
                'genero' => 'Battle Royale',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-05-04',
                'modalidad' => 'individual',
                'img_id' => 'fornite',
            ],
            [
                'nombre' => 'FIFA',
                'genero' => 'Sports',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-05-04',
                'modalidad' => 'individual',
                'img_id' => 'fifa',
            ],
            [
                'nombre' => 'Dragon Ball',
                'genero' => 'Peleas',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-05-04',
                'modalidad' => 'individual',
                'img_id' => 'dragon_ball',
            ],
            [
                'nombre' => 'League of Legends',
                'genero' => 'MOBA',
                'costo_inscripcion' => 25.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'grupo',
                'img_id' => 'lol',
            ],
            [
                'nombre' => 'Valorant',
                'genero' => 'FPS',
                'costo_inscripcion' => 25.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'grupo',
                'img_id' => 'valorant',
            ],
            [
                'nombre' => 'Free Fire',
                'genero' => 'Battle Royale',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'individual',
                'img_id' => 'free_ind',
            ],
            [
                'nombre' => 'Free Fire',
                'genero' => 'Battle Royale',
                'costo_inscripcion' => 25.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'grupo',
                'img_id' => 'free_gru',
            ],
            [
                'nombre' => 'Mortal Kombat',
                'genero' => 'Peleas',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'individual',
                'img_id' => 'mortal',
            ],
            [
                'nombre' => 'Clash Royale',
                'genero' => 'Estrategia',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'individual',
                'img_id' => 'clash',
            ],
            [
                'nombre' => 'Tetris',
                'genero' => 'Logica',
                'costo_inscripcion' => 3.00,
                'fecha_limite_inscripcion' => '2025-06-02',
                'modalidad' => 'individual',
                'img_id' => 'tetris',
            ],
        ];

        foreach ($juegos as $juego) {
            Juego::firstOrCreate($juego);
        }
    }
}
