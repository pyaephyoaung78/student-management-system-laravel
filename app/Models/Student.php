<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Guardian;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_code',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'address',
        'status',
        'course_id',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class)->oldestOfMany();
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function activeEnrollment()
    {
        return $this->hasOne(Enrollment::class)->where('status', 'active')->latestOfMany();
    }
}
