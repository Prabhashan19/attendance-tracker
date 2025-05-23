<?php

namespace App\Models;

// Import necessary Laravel classes
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// The Student model represents a record in the 'students' database table
class Student extends Model
{
    use HasFactory;

    // Specify which attributes are mass assignable
    // This protects against mass assignment vulnerabilities
    protected $fillable = ['registration_number', 'name', 'email'];

    /**
     * Define a many-to-many relationship between Student and Subject.
     * Each student can be enrolled in multiple subjects.
     * A pivot table (e.g., 'student_subject') is expected to exist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * Define a one-to-many relationship between Student and Attendance.
     * This means a student can have many attendance records.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
