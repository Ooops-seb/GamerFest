<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Juego;

class JuegoController extends Controller
{
    public function index()
    {
        try {
            $juegos = Juego::all();
            return response()->json($juegos);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error al obtener juegos: " . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'genero' => 'required|string|max:255',
                'costo_inscripcion' => 'required|numeric',
                'fecha_limite_inscripcion' => 'required|date',
                'modalidad' => 'required|in:individual,grupo',
            ]);

            $juego = Juego::create($data);

            return response()->json([
                'data' => $juego,
                'message' => 'Juego creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error al crear el juego: " . $e->getMessage()], 500);
        }
    }

    public function show(Juego $juego)
    {
        try {
            return response()->json($juego);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error al obtener el juego: " . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Juego $juego)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'genero' => 'required|string|max:255',
                'costo_inscripcion' => 'required|numeric',
                'fecha_limite_inscripcion' => 'required|date',
                'modalidad' => 'required|in:individual,grupo',
            ]);

            $juego->update($data);

            return response()->json([
                'data' => $juego,
                'message' => 'Juego actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error al actualizar el juego: " . $e->getMessage()], 500);
        }
    }

    public function destroy(Juego $juego)
    {
        try {
            $juego->delete();

            return response()->json([
                'message' => 'Juego eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => "Error al eliminar el juego: " . $e->getMessage()], 500);
        }
    }



    public function cargarCarrito(Request $request)
    {
        $juegosInscritos = $request->input('juegosInscritos');

        $idsJuegosInscritos = array_map(function ($juego) {
            return $juego['id'];
        }, $juegosInscritos);

        $juegos = Juego::findMany($idsJuegosInscritos);
        $total = $juegos->sum('costo_inscripcion');

        return response()->json([
            'juegos' => $juegos,
            'total' => $total,
        ]);
    }
}
