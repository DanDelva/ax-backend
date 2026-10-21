<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    // RF1.1 - Consulta de Calificaciones (Teóricas y Prácticas) [cite: 30]
    public function getGrades(Request $request)
    {
        $grades = Grade::with('subject')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $grades
        ]);
    }

    // RF1.3 - Temarios, Manuales y Listas de Materiales [cite: 32]
    public function getSubjects(Request $request)
    {
        $subjects = Subject::all(); 

        return response()->json([
            'status' => 'success',
            'data' => $subjects
        ]);
    }
}