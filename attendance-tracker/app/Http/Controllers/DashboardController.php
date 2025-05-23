<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Set default date range: last 7 days
        $defaultStartDate = Carbon::now()->subWeek()->format('Y-m-d');
        $defaultEndDate = Carbon::now()->format('Y-m-d');
        
        // Get all subjects to populate the filter dropdown
        $subjects = Subject::all();
        
        // Retrieve filter parameters from the request
        $subjectId = $request->input('subject_id');
        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);
        
        /**
     * Teacher load students along with their attendances filtered by:
     * - date range
     * - optional subject ID
     */
        $query = Student::with([
            'attendances' => function($q) use ($subjectId, $startDate, $endDate) {
                $q->select('id', 'student_id', 'subject_id', 'date', 'present')
                  ->whereBetween('date', [$startDate, $endDate]);

                // Filter by subject if selected
                if ($subjectId) {
                    $q->where('subject_id', $subjectId);
                }
            }
        ])->select('id', 'name', 'registration_number'); // Only load necessary columns
        
        // Optional search filter: by name or registration number
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }
        
        // Paginate the results (20 per page)
        $students = $query->paginate(20);
        
        // Calculate attendance percentage for each student within the selected date range and subject
        $students->each(function($student) use ($subjectId, $startDate, $endDate) {
            $query = $student->attendances()
                ->whereBetween('date', [$startDate, $endDate]);
            
            if ($subjectId) {
                $query->where('subject_id', $subjectId);
            }
            
            // Count total and present classes
            $totalClasses = $query->count();
            $presentClasses = $query->where('present', true)->count();
            
            // Calculate percentage
            $student->attendance_percentage = $totalClasses > 0 
                ? round(($presentClasses / $totalClasses) * 100, 2)
                : 0;
        });
        
        // Return data to the dashboard view
        return view('dashboard', compact(
            'students', 
            'subjects',
            'subjectId',
            'startDate',
            'endDate'
        ));
    }
}
