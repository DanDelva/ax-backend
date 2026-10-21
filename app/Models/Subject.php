<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialty_id',
        'title',
        'content_manual',
        'material_list',
        'teacher_id' // Added per plan: to enforce teacher-subject relation. You should add this column to subjects migration.
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}