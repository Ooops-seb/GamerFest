<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB as FacadesDB;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $jugadorRole = Role::firstOrCreate(['name' => 'jugador']);

        $adminPermissions = [
            'create_juego',
            'delete_juego',
            'update_juego',
            'create_ganador_individual',
            'delete_ganador_individual',
            'update_ganador_individual',
            'create_ganador_grupal',
            'delete_ganador_grupal',
            'update_ganador_grupal'
        ];

        $jugadorPermissions = [
            'view_self_user',
            'logout_self',
            'view_participantes',
            'view_self_participante',
            'create_participante',
            'delete_self_participante',
            'update_self_participante',
            'view_equipos',
            'view_self_equipo',
            'create_equipo',
            'delete_self_equipo',
            'update_self_equipo',
            'view_ganadores_individuales',
            'view_self_ganador_individual',
            'view_ganadores_grupales',
            'view_self_ganador_grupal',
            'view_inscripciones_individuales',
            'view_self_inscripcion_individual',
            'create_inscripcion_individual',
            'delete_self_inscripcion_individual',
            'update_self_inscripcion_individual',
            'view_inscripciones_grupales',
            'view_self_inscripcion_grupal',
            'create_inscripcion_grupal',
            'delete_self_inscripcion_grupal',
            'update_self_inscripcion_grupal'
        ];

        // Crear los permisos si no existen
        foreach (array_merge($adminPermissions, $jugadorPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a los roles usando syncPermissions
        $adminRole->syncPermissions($adminPermissions);
        $jugadorRole->syncPermissions($jugadorPermissions);

        $adminUser = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@ooops.dev',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole($adminRole);

        $playerUser = User::firstOrCreate([
            'name' => 'Jugador',
            'email' => 'test@ooops.dev',
            'password' => Hash::make('jugador'),
            'email_verified_at' => now(),
        ]);
        $playerUser->assignRole($jugadorRole);
    }
}
