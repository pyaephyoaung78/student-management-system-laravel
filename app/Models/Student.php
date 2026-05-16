<?php

namespace App\Models;

use App\Models\Course;

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
}
