<?php

namespace App\Models;

// Import necessary Laravel classes
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// The Subject model represents a subject/course in the 'subjects' table
class Subject extends Model
{
    // Include the HasFactory trait to enable model factory support
    use HasFactory;

    // Define which attributes can be mass assigned
    protected $fillable = ['code', 'name'];

    /**
     * Define a many-to-many relationship between Subject and Student.
     * This indicates that a subject can be taken by many students,
     * and a student can be enrolled in many subjects.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    /**
     * Define a one-to-many relationship between Subject and Attendance.
     * This means one subject can have many attendance records,
     * typically one for each student enrolled.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
