<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'matricula',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
       // 'password' => 'hashed',
    ];

    // Helper functions for roles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDocente(): bool
    {
        return $this->role === 'docente';
    }

    public function isAlumno(): bool
    {
        return $this->role === 'estudiante'; // The DB migration says 'estudiante' but your prompt said 'alumno'. Keeping the DB default 'estudiante'.
    }

    // Relación: Un estudiante/docente tiene inscripciones
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relación: Un estudiante tiene muchas calificaciones 
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Relación: Un estudiante tiene muchos pagos
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relación: Un estudiante tiene justificantes
    public function excuses()
    {
        return $this->hasMany(Excuse::class);
    }

    // Relación: Un estudiante tiene asistencias
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}