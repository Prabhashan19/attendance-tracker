<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks for faster inserts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Create subjects with batch insert
        $subjectData = [];
        for ($i = 0; $i < 5; $i++) {
            $subjectData[] = [
                'code' => 'SUB' . rand(100, 999),
                'name' => 'Subject ' . ($i + 1),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Subject::insert($subjectData);
        $subjects = Subject::all();

        // Create students with batch insert
        $studentData = [];
        $usedRegistrationNumbers = [];
        
        for ($i = 0; $i < 1000; $i++) {
            do {
                $regNumber = 'STD' . rand(100000, 999999);
            } while (in_array($regNumber, $usedRegistrationNumbers));
            
            $usedRegistrationNumbers[] = $regNumber;
            
            $studentData[] = [
                'registration_number' => $regNumber,
                'name' => 'Student ' . ($i + 1),
                'email' => 'student' . ($i + 1) . '@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Student::insert($studentData);
        $students = Student::all();

        // Pre-calculate enrollment assignments
        $enrollments = [];
        foreach ($students as $student) {
            $enrolledSubjects = $subjects->random(rand(3, 5));
            $enrollments[$student->id] = $enrolledSubjects->pluck('id')->toArray();
        }

        // Bulk create attendance records
        $attendanceData = [];
        $batchSize = 1000; // Process in batches to avoid memory issues
        
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            
            foreach ($enrollments as $studentId => $subjectIds) {
                foreach ($subjectIds as $subjectId) {
                    $attendanceData[] = [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'date' => $date,
                        'present' => rand(0, 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    // Insert in batches to avoid memory overflow
                    if (count($attendanceData) >= $batchSize) {
                        Attendance::insert($attendanceData);
                        $attendanceData = [];
                    }
                }
            }
        }
        
        // Insert remaining records
        if (!empty($attendanceData)) {
            Attendance::insert($attendanceData);
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}