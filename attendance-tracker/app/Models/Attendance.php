<?php

namespace App\Models;

// Importing necessary Laravel classes
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// The Student model represents a student record in the 'students' table
class Attendance extends Model
{
    use HasFactory;

     // Specify which attributes are mass assignable
    protected $fillable = ['student_id', 'subject_id', 'date', 'present'];

    protected $dates = ['date'];

    /**
     * Define a many-to-many relationship between Student and Subject.
     * This means a student can be enrolled in multiple subjects,
     * and each subject can have multiple students.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Define a one-to-many relationship between Student and Attendance.
     * This means a student can have many attendance records.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
