@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Daily Attendance Marking</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('attendances.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject_id" class="font-weight-bold">Select Subject</label>
                            <select name="subject_id" id="subject_id" class="form-control" required>
                                <option value="">-- Choose Subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->code }} - {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date" class="font-weight-bold">Attendance Date</label>
                            <input type="date" name="date" id="date" class="form-control" required 
                                   value="{{ old('date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                
                <div class="mt-4" id="students-container">
                    <div class="alert alert-info">
                        Please select a subject to view enrolled students
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Attendance
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject_id');
    const dateInput = document.getElementById('date');
    const studentsContainer = document.getElementById('students-container');
    
    // Load students when subject changes
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        const date = dateInput.value;
        
        if (!subjectId) {
            studentsContainer.innerHTML = `
                <div class="alert alert-info">
                    Please select a subject to view enrolled students
                </div>`;
            return;
        }
        
        // Show loading indicator
        studentsContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p>Loading students...</p>
            </div>`;
        
        // Fetch students for selected subject
        fetch(`/api/students-by-subject/${subjectId}?date=${date}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(students => {
                if (students.length === 0) {
                    studentsContainer.innerHTML = `
                        <div class="alert alert-warning">
                            No students enrolled in this subject
                        </div>`;
                    return;
                }
                
                let html = `
                    <h4 class="mb-3">Enrolled Students</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Registration No</th>
                                    <th class="text-center">Present</th>
                                </tr>
                            </thead>
                            <tbody>`;
                
                students.forEach((student, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${student.name}</td>
                            <td>${student.registration_number}</td>
                            <td class="text-center">
                                <div class="form-check d-inline-block">
                                    <input type="hidden" name="students[${student.id}]" value="0">
                                    <input class="form-check-input" type="checkbox" 
                                           name="students[${student.id}]" value="1" checked
                                           style="transform: scale(1.5);">
                                </div>
                            </td>
                        </tr>`;
                });
                
                html += `</tbody></table></div>`;
                studentsContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                studentsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        Failed to load students. Please try again.
                    </div>`;
            });
    });
    
    // Optional: Refresh students when date changes
    dateInput.addEventListener('change', function() {
        if (subjectSelect.value) {
            subjectSelect.dispatchEvent(new Event('change'));
        }
    });
});
</script>

<style>
    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
@endsection