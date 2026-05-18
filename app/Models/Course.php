<?php

namespace App\Models;
use App\Models\Enrollment;
use App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    protected $fillable = [
        'code',
        'name',
        'description',
        'duration_months',
        'status',
    ];
    
    //
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
