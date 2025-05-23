<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Http\Request;

// This controller handles attendance-related actions
class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Placeholder for showing a list of attendance records
    }

    /**
     * Show the form for creating a new attendance entry.
     * Retrieves all subjects to populate the subject selection dropdown in the form.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // Fetch all subjects from the database
        $subjects = Subject::all();

        // Return the view for creating attendance, passing the subjects to it
        return view('attendances.create', compact('subjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data to ensure all required fields are present and correct
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'students' => 'required|array',
            'students.*' => 'exists:students,id'
        ]);
        
        // Loop through each student and create or update their attendance record
        foreach ($validated['students'] as $studentId => $present) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $validated['subject_id'],
                    'date' => $validated['date']
                ],
                ['present' => $present]
            );
    }

    // Redirect to the dashboard with a success message
    return redirect()->route('dashboard')->with('success', 'Attendance recorded successfully');
    }
}
