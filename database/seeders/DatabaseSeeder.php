<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Excuse;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $plainPassword = config('app.seed_password');
        if (empty($plainPassword)) {
            throw new \RuntimeException('Define SEED_DEFAULT_PASSWORD en tu archivo .env antes de ejecutar los seeders.');
        }
        $password = Hash::make($plainPassword);

        // ===================== ADMINS (2) =====================
        $admin1 = User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin@gmail.com',
            'password' => $password,
            'role' => 'admin',
            'phone' => '757' . rand(1000000, 9999999),
            'matricula' => 'AD01',
        ]);

        $admin2 = User::create([
            'name' => 'Laura Directora',
            'email' => 'laura@gmail.com',
            'password' => $password,
            'role' => 'admin',
            'phone' => '757' . rand(1000000, 9999999),
            'matricula' => 'AD02',
        ]);

        // ===================== DOCENTES (3) =====================
        $profMaria = User::create([
            'name' => 'María García López',
            'email' => 'mariadocente@gmail.com',
            'password' => $password,
            'role' => 'docente',
            'phone' => '757' . rand(1000000, 9999999),
            'matricula' => 'PR01',
        ]);

        $profCarlos = User::create([
            'name' => 'Carlos Hernández Ruiz',
            'email' => 'carlosdocente@gmail.com',
            'password' => $password,
            'role' => 'docente',
            'phone' => '757' . rand(1000000, 9999999),
            'matricula' => 'PR02',
        ]);

        $profAna = User::create([
            'name' => 'Ana Martínez Soto',
            'email' => 'anadocente@gmail.com',
            'password' => $password,
            'role' => 'docente',
            'phone' => '757' . rand(1000000, 9999999),
            'matricula' => 'PR03',
        ]);

        // ===================== ESTUDIANTES (18: 13 mujeres, 5 hombres) =====================
        $nombresAlumnos = [
            // Mujeres (13)
            ['Sofía Ramírez', 'sofia.ramirez@gmail.com'],
            ['Valentina Cruz', 'valentina.cruz@gmail.com'],
            ['Camila Reyes', 'camila.reyes@gmail.com'],
            ['Isabella Ortiz', 'isabella.ortiz@gmail.com'],
            ['Lucía Mendoza', 'lucia.mendoza@gmail.com'],
            ['Renata Jiménez', 'renata.jimenez@gmail.com'],
            ['Regina Peña', 'regina.pena@gmail.com'],
            ['Mariana Silva', 'mariana.silva@gmail.com'],
            ['Paula Guerrero', 'paula.guerrero@gmail.com'],
            ['Fernanda Delgado', 'fernanda.delgado@gmail.com'],
            ['Andrea Castro', 'andrea.castro@gmail.com'],
            ['Valeria Nuñez', 'valeria.nunez@gmail.com'],
            ['Ximena Vargas', 'ximena.vargas@gmail.com'],
            // Hombres (5)
            ['Pedro Gómez', 'pedro.gomez@gmail.com'],
            ['Diego Torres', 'diego.torres@gmail.com'],
            ['Mateo Flores', 'mateo.flores@gmail.com'],
            ['Sebastián Morales', 'sebastian.morales@gmail.com'],
            ['Yoyar Creador', 'yoyar.creador@gmail.com'], // El usuario :D
        ];

        $estudiantes = collect();
        foreach ($nombresAlumnos as $i => $data) {
            $numMatricula = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
            // El usuario vive por Tlapa (757) y su num es 7571251155, se lo asignamos a él, y a los demas random
            $phone = ($data[0] === 'Yoyar Creador') ? '7571251155' : '757' . rand(1000000, 9999999);
            
            $estudiantes->push(User::create([
                'name' => $data[0],
                'email' => $data[1],
                'password' => $password,
                'role' => 'estudiante',
                'phone' => $phone,
                'matricula' => 'AL' . $numMatricula,
            ]));
        }

        // ===================== ESPECIALIDADES (3) =====================
        $espEstilismo = Specialty::create([
            'name' => 'Estilismo y Corte',
            'description' => 'Técnicas profesionales de corte, peinado y estilismo capilar.',
        ]);

        $espCosmetologia = Specialty::create([
            'name' => 'Cosmetología y Maquillaje',
            'description' => 'Maquillaje profesional, cuidado facial y cosmetología avanzada.',
        ]);

        $espUnas = Specialty::create([
            'name' => 'Uñas y Nail Art',
            'description' => 'Aplicación de uñas acrílicas, gel y diseño artístico.',
        ]);

        // ===================== MATERIAS (2 por especialidad = 6 total) =====================
        // Estilismo - Profa. María
        $matCorte = Subject::create([
            'specialty_id' => $espEstilismo->id,
            'teacher_id' => $profMaria->id,
            'title' => 'Corte de Cabello',
            'content_manual' => 'Manual de técnicas de corte: capas, degrafilado, bob, pixie.',
            'material_list' => 'Tijeras, navaja, peines, capa de corte',
        ]);

        $matPeinado = Subject::create([
            'specialty_id' => $espEstilismo->id,
            'teacher_id' => $profMaria->id,
            'title' => 'Peinado y Alisado',
            'content_manual' => 'Técnicas de alisado, rizos, peinados de fiesta.',
            'material_list' => 'Plancha, tenaza, secadora, spray fijador',
        ]);

        // Cosmetología - Prof. Carlos
        $matMaquillaje = Subject::create([
            'specialty_id' => $espCosmetologia->id,
            'teacher_id' => $profCarlos->id,
            'title' => 'Maquillaje Profesional',
            'content_manual' => 'Base, contorno, ojos, labios, maquillaje de novia.',
            'material_list' => 'Brochas, paletas, bases, primers',
        ]);

        $matFacial = Subject::create([
            'specialty_id' => $espCosmetologia->id,
            'teacher_id' => $profCarlos->id,
            'title' => 'Tratamiento Facial',
            'content_manual' => 'Limpieza profunda, exfoliación, mascarillas y masajes.',
            'material_list' => 'Vaporizador, cremas, mascarillas, guantes',
        ]);

        // Uñas - Profa. Ana
        $matAcrilico = Subject::create([
            'specialty_id' => $espUnas->id,
            'teacher_id' => $profAna->id,
            'title' => 'Uñas Acrílicas',
            'content_manual' => 'Aplicación de acrílico, esculpido, relleno y retiro.',
            'material_list' => 'Polvo acrílico, monómero, tips, lima',
        ]);

        $matNailArt = Subject::create([
            'specialty_id' => $espUnas->id,
            'teacher_id' => $profAna->id,
            'title' => 'Nail Art y Diseño',
            'content_manual' => 'Diseños artísticos, encapsulado, foil, stamping.',
            'material_list' => 'Pinceles finos, glitter, foil, stamping plates',
        ]);

        $todasMaterias = collect([$matCorte, $matPeinado, $matMaquillaje, $matFacial, $matAcrilico, $matNailArt]);

        // ===================== HORARIOS =====================
        $horarios = [
            [$matCorte->id, 'Lunes', '08:00', '10:00', 'Salón A1'],
            [$matCorte->id, 'Miércoles', '08:00', '10:00', 'Salón A1'],
            [$matPeinado->id, 'Martes', '10:00', '12:00', 'Salón A2'],
            [$matPeinado->id, 'Jueves', '10:00', '12:00', 'Salón A2'],
            [$matMaquillaje->id, 'Lunes', '10:00', '12:00', 'Salón B1'],
            [$matMaquillaje->id, 'Viernes', '08:00', '10:00', 'Salón B1'],
            [$matFacial->id, 'Martes', '08:00', '10:00', 'Salón B2'],
            [$matFacial->id, 'Jueves', '08:00', '10:00', 'Salón B2'],
            [$matAcrilico->id, 'Miércoles', '10:00', '12:00', 'Salón C1'],
            [$matAcrilico->id, 'Viernes', '10:00', '12:00', 'Salón C1'],
            [$matNailArt->id, 'Lunes', '14:00', '16:00', 'Salón C2'],
            [$matNailArt->id, 'Jueves', '14:00', '16:00', 'Salón C2'],
        ];

        foreach ($horarios as $h) {
            Schedule::create([
                'subject_id' => $h[0],
                'day_of_week' => $h[1],
                'start_time' => $h[2],
                'end_time' => $h[3],
                'classroom' => $h[4],
            ]);
        }

        // ===================== INSCRIPCIONES =====================
        // 6 alumnos por especialidad
        foreach ($estudiantes as $i => $alumno) {
            if ($i < 6) {
                $esp = $espEstilismo;
                $grupo = 'Grupo 1A';
            } elseif ($i < 12) {
                $esp = $espCosmetologia;
                $grupo = 'Grupo 2A';
            } else {
                $esp = $espUnas;
                $grupo = 'Grupo 3A';
            }

            Enrollment::create([
                'user_id' => $alumno->id,
                'specialty_id' => $esp->id,
                'group_name' => $grupo,
            ]);
        }

        // ===================== CALIFICACIONES =====================
        foreach ($estudiantes as $i => $alumno) {
            if ($i < 6) {
                $materias = [$matCorte, $matPeinado];
            } elseif ($i < 12) {
                $materias = [$matMaquillaje, $matFacial];
            } else {
                $materias = [$matAcrilico, $matNailArt];
            }

            foreach ($materias as $materia) {
                Grade::create([
                    'user_id' => $alumno->id,
                    'subject_id' => $materia->id,
                    'score' => round(rand(60, 100) / 10, 2),
                    'type' => 'teorica',
                ]);
                Grade::create([
                    'user_id' => $alumno->id,
                    'subject_id' => $materia->id,
                    'score' => round(rand(70, 100) / 10, 2),
                    'type' => 'practica',
                ]);
            }
        }

        // ===================== PAGOS =====================
        $conceptos = ['Inscripción', 'Mensualidad', 'Material', 'Certificación'];
        $estados = ['pendiente', 'validado', 'validado', 'validado']; // 75% validados

        foreach ($estudiantes as $alumno) {
            for ($p = 0; $p < 3; $p++) {
                Payment::create([
                    'user_id' => $alumno->id,
                    'amount' => [500, 1000, 1500, 2000][array_rand([500, 1000, 1500, 2000])],
                    'concept' => $conceptos[array_rand($conceptos)],
                    'status' => $estados[array_rand($estados)],
                    'payment_date' => now()->subDays(rand(1, 90))->format('Y-m-d'),
                ]);
            }
        }

        // ===================== ASISTENCIAS =====================
        $statusAsis = ['presente', 'presente', 'presente', 'falta', 'retardo'];

        foreach ($estudiantes as $i => $alumno) {
            if ($i < 6) {
                $materia = $matCorte;
            } elseif ($i < 12) {
                $materia = $matMaquillaje;
            } else {
                $materia = $matAcrilico;
            }

            for ($d = 0; $d < 5; $d++) {
                Attendance::create([
                    'user_id' => $alumno->id,
                    'subject_id' => $materia->id,
                    'date' => now()->subDays($d)->format('Y-m-d'),
                    'status' => $statusAsis[array_rand($statusAsis)],
                ]);
            }
        }

        // ===================== JUSTIFICANTES =====================
        $alumnosConFalta = $estudiantes->take(6);
        foreach ($alumnosConFalta as $alumno) {
            Excuse::create([
                'user_id' => $alumno->id,
                'reason' => ['Cita médica', 'Enfermedad', 'Asunto familiar', 'Trámite escolar'][array_rand(['Cita médica', 'Enfermedad', 'Asunto familiar', 'Trámite escolar'])],
                'date' => now()->subDays(rand(1, 10))->format('Y-m-d'),
                'status' => ['pendiente', 'aprobado', 'pendiente'][array_rand(['pendiente', 'aprobado', 'pendiente'])],
            ]);
        }

        // ===================== RESUMEN =====================
        echo "\n✅ Base de datos poblada exitosamente:\n";
        echo "- 2 Admins\n";
        echo "- 3 Docentes (María, Carlos, Ana)\n";
        echo "- 18 Estudiantes (70% mujeres)\n";
        echo "- 3 Especialidades\n";
        echo "- 6 Materias (2 por especialidad)\n";
        echo "- 12 Horarios\n";
        echo "- 18 Inscripciones\n";
        echo "- 72 Calificaciones\n";
        echo "- 54 Pagos\n";
        echo "- 90 Asistencias\n";
        echo "- 6 Justificantes\n";
        echo "\n🔑 Contraseña para todos: la definida en SEED_DEFAULT_PASSWORD\n";
    }
}
