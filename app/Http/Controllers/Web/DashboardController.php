<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Excuse;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('dashboard', [
                'role' => 'admin',
                'totalUsers' => User::count(),
                'totalStudents' => User::where('role', 'estudiante')->count(),
                'totalTeachers' => User::where('role', 'docente')->count(),
                'totalSpecialties' => Specialty::count(),
                'totalSubjects' => Subject::count(),
                'totalEnrollments' => Enrollment::count(),
                'pendingPayments' => Payment::where('status', 'pendiente')->count(),
                'pendingExcuses' => Excuse::where('status', 'pendiente')->count(),
            ]);
        }

        if ($user->isDocente()) {
            $mySubjectIds = Subject::where('teacher_id', $user->id)->pluck('id');
            return view('dashboard', [
                'role' => 'docente',
                'totalSubjects' => $mySubjectIds->count(),
                'totalStudents' => Grade::whereIn('subject_id', $mySubjectIds)->distinct('user_id')->count('user_id'),
                'totalGrades' => Grade::whereIn('subject_id', $mySubjectIds)->count(),
                'totalAttendances' => Attendance::whereIn('subject_id', $mySubjectIds)->count(),
            ]);
        }

        // Estudiante
        return view('dashboard', [
            'role' => 'estudiante',
            'totalGrades' => $user->grades()->count(),
            'totalPayments' => $user->payments()->count(),
            'pendingPayments' => $user->payments()->where('status', 'pendiente')->count(),
            'totalAttendances' => $user->attendances()->count(),
            'totalExcuses' => $user->excuses()->count(),
        ]);
    }
}
