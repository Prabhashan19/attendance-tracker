@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Attendance Dashboard</h2>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subject_id">Subject</label>
                            <select name="subject_id" id="subject_id" class="form-control">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="form-control" value="{{ $startDate }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control" value="{{ $endDate }}">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search Student</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" 
                                       class="form-control" placeholder="Name or Reg No" 
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Reg No</th>
                            <th>Name</th>
                            <th>Present</th>
                            <th>Total</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->registration_number }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->attendances->where('present', true)->count() }}</td>
                                <td>{{ $student->attendances->count() }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar {{ $student->attendance_percentage < 75 ? 'bg-danger' : 'bg-success' }}" 
                                             role="progressbar" 
                                             style="width: {{ $student->attendance_percentage }}%" 
                                             aria-valuenow="{{ $student->attendance_percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $student->attendance_percentage }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection