<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InscripcionGrupal;
use App\Models\InscripcionIndividual;
use App\Models\Juego;
use App\Models\Sponsor;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Application;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{

    public function index()
    {
        $inscripciones_data = $this->get_inscripciones_data();

        // Obtener todos los juegos
        $juegos = Juego::all();
        $inscritosPorJuego = [];
        foreach ($juegos as $juego) {
            $individuales = $juego->inscripcionesIndividuales()->count();
            $grupales = $juego->inscripcionesGrupales()->count();
            $inscritosPorJuego[] = [
                'nombre' => $juego->nombre,
                'total' => $individuales + $grupales,
                'individuales' => $individuales,
                'grupales' => $grupales,
                'modalidad' => $juego->modalidad, // <-- AGREGAR ESTO
            ];
        }

        // NUEVO: contar sponsors y usuarios
        $totalSponsors = Sponsor::count();
        $totalUsuarios = User::count();

        // Obtener las 10 últimas inscripciones (mezcla de individuales y grupales)
        $ultimasIndividuales = InscripcionIndividual::with('user', 'juego')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($inscripcion) {
                return [
                    'tipo' => 'Individual',
                    'nombre' => $inscripcion->user ? $inscripcion->user->name : '',
                    'telefono' => $inscripcion->user ? $inscripcion->user->phone : '',
                    'juego' => $inscripcion->juego ? $inscripcion->juego->nombre : '',
                    'fecha' => $inscripcion->created_at ? $inscripcion->created_at->format('Y-m-d H:i') : '',
                    'estado_pago' => $inscripcion->estado_pago,
                ];
            });
        $ultimasGrupales = InscripcionGrupal::with('equipo.user', 'juego')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($inscripcion) {
                return [
                    'tipo' => 'Grupal',
                    'nombre' => $inscripcion->equipo && $inscripcion->equipo->user ? $inscripcion->equipo->user->name : '',
                    'telefono' => $inscripcion->equipo && $inscripcion->equipo->user ? $inscripcion->equipo->user->phone : '',
                    'juego' => $inscripcion->juego ? $inscripcion->juego->nombre : '',
                    'equipo' => $inscripcion->equipo ? $inscripcion->equipo->nombre_equipo : '',
                    'fecha' => $inscripcion->created_at ? $inscripcion->created_at->format('Y-m-d H:i') : '',
                    'estado_pago' => $inscripcion->estado_pago,
                ];
            });
        $ultimasInscripciones = collect($ultimasIndividuales)->merge($ultimasGrupales)->sortByDesc('fecha')->take(10)->values();

        return Inertia::render('Dashboard', [
            'role' => Auth::check() && Auth::user()->hasRole("admin"),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'inscripciones_data' => $inscripciones_data,
            'inscritos_por_juego' => $inscritosPorJuego,
            'total_sponsors' => $totalSponsors,
            'total_usuarios' => $totalUsuarios,
            'ultimas_inscripciones' => $ultimasInscripciones,
        ]);
    }


    private function get_inscripciones_data()
    {
        // Obtener el número de inscripciones grupales
        $numeroInscripcionesGrupales = InscripcionGrupal::count();

        // Obtener el número de inscripciones individuales
        $numeroInscripcionesIndividuales = InscripcionIndividual::count();

        // SUMA REAL de los valores pagados por inscripciones grupales
        $sumaInscripcionesGrupales = InscripcionGrupal::whereNotNull('valor_comprobante')->sum('valor_comprobante');

        // SUMA REAL de los valores pagados por inscripciones individuales
        $sumaInscripcionesIndividuales = InscripcionIndividual::whereNotNull('valor_comprobante')->sum('valor_comprobante');

        // Calcular el ingreso total REAL
        $ingresosTotales = $sumaInscripcionesGrupales + $sumaInscripcionesIndividuales;

        return [
            'numero_inscripciones_grupales' => $numeroInscripcionesGrupales,
            'numero_inscripciones_individuales' => $numeroInscripcionesIndividuales,
            'numero_inscripciones_totales' => ($numeroInscripcionesGrupales + $numeroInscripcionesIndividuales),
            'suma_inscripciones_grupales' => $sumaInscripcionesGrupales,
            'suma_inscripciones_individuales' => $sumaInscripcionesIndividuales,
            'ingresos_totales' => $ingresosTotales
        ];
    }


    public function list_reportes()
    {
        return Inertia::render('DashboardPage/ParticipantesPage/ListReportes', [
            'role' => Auth::user()->hasRole("admin"),
        ]);
    }

    public function mis_equipos()
    {
        return Inertia::render('DashboardPage/ParticipantesPage/EquiposPage', [
            'user_id' => Auth::user()->id,
            'role' => Auth::user()->hasRole("admin"),
        ]);
    }


    public function get_comprobante($rutaImagen)
    {
        // Verificamos que el archivo exista
        if (Storage::exists('/app/' . $rutaImagen)) {
            // Obtenemos el contenido del archivo
            $file = Storage::get('/app/' . $rutaImagen);

            // Creamos una respuesta con el contenido del archivo
            $response = response($file, 200);

            // Definimos el tipo de contenido en la respuesta
            $response->header("Content-Type", Storage::mimeType($rutaImagen));

            // Retornamos la respuesta
            return $response;
        } else {
            // Si el archivo no existe, retornamos un mensaje de error
            return response()->json(['error' => 'File not found.'], 404);
        }
    }

}
