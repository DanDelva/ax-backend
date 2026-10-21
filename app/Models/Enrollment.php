<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Added per plan: student who is enrolled. You need to add this to migration.
        'specialty_id', // Added per plan: the course they are enrolled in. You need to add this to migration.
        'group_name', // Added per plan: grouping (e.g. Generation 1). You need to add this to migration.
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
