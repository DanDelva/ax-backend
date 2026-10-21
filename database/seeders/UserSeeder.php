<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $plainPassword = config('app.seed_password');
        if (empty($plainPassword)) {
            throw new \RuntimeException('Define SEED_DEFAULT_PASSWORD en tu archivo .env antes de ejecutar los seeders.');
        }

        // 1. Creamos la Especialidad (Basado en el sector de Belleza [cite: 25])
        $especialidad = Specialty::create([
            'name' => 'Estilismo y Bienestar Integral',
            'description' => 'Formación técnica profesional'
        ]);

        // 2. Creamos una Materia con su Manual y Materiales (RF1.3 [cite: 32])
        $materia = Subject::create([
            'specialty_id' => $especialidad->id,
            'title' => 'Colorimetría Básica',
            'content_manual' => 'Manual técnico sobre círculos cromáticos y mezclas.',
            'material_list' => 'Brochas, bowls, capas y guantes de nitrilo.'
        ]);

        // 3. Creamos al Estudiante de Prueba [cite: 8, 30]
        $alumno = User::create([
            'name' => 'Estudiante Prueba AX',
            'email' => 'alumno@ax.com',
            'password' => Hash::make($plainPassword),
            'role' => 'estudiante',
            'phone' => '2221234567'
        ]);

        // 4. Le asignamos una Calificación en tiempo real (RF1.1 [cite: 30])
        Grade::create([
            'user_id' => $alumno->id,
            'subject_id' => $materia->id,
            'score' => 9.5,
            'type' => 'practica' // Diferenciación entre teoría y práctica [cite: 30]
        ]);

        // 5. Usuario Administrador [cite: 56]
        User::create([
            'name' => 'Admin AX',
            'email' => 'admin@ax.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 6. Usuario Maestro
        User::create([
            'name' => 'Maestro AX',
            'email' => 'maestro@ax.com',
            'password' => Hash::make('maestro123'),
            'role' => 'maestro',
            'phone' => '2227654321'
        ]);
    }
}