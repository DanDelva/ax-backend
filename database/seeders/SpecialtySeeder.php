<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        // Crear Especialidad [cite: 25]
        $estilismo = Specialty::create([
            'name' => 'Estilismo y Bienestar Integral',
            'description' => 'Formación técnica profesional en belleza y estética.'
        ]);

        // Crear Materias con Manuales y Materiales 
        Subject::create([
            'specialty_id' => $estilismo->id,
            'title' => 'Colorimetría Básica',
            'content_manual' => 'Manual de mezclas, círculos cromáticos y aplicación de tintes.',
            'material_list' => 'Brochas, bowls, capa de tinte, guantes de nitrilo y peróxidos.'
        ]);

        Subject::create([
            'specialty_id' => $estilismo->id,
            'title' => 'Corte de Dama',
            'content_manual' => 'Técnicas de partición, grados de elevación y diseño de siluetas.',
            'material_list' => 'Tijeras de filo dulce, peine de cola, atomizador y bledo.'
        ]);
    }
}