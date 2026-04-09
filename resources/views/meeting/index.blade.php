@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Meetings</h4>
                    <a href="{{ route('meeting.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Meeting
                    </a>
                </div>
                
                <div class="card-body">
                    @if($meetings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Meeting Code</th>
                                        <th>Status</th>
                                        <th>Start Time</th>
                                        <th>Duration</th>
                                        <th>Your Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meetings as $meeting)
                                        <tr>
                                            <td>
                                                <code>{{ $meeting->code }}</code>
                                                @if($meeting->host_id == auth()->id())
                                                    <span class="badge bg-warning text-dark ms-1">Host</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($meeting->start_time)
                                                    @if(now()->lt($meeting->start_time))
                                                        <span class="badge bg-info">Scheduled</span>
                                                    @elseif(now()->gt($meeting->start_time->copy()->addMinutes($meeting->duration_minutes)))
                                                        <span class="badge bg-secondary">Ended</span>
                                                    @else
                                                        <span class="badge bg-success">Active</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Not Scheduled</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($meeting->start_time)
                                                    {{ $meeting->start_time->format('M j, Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($meeting->duration_minutes)
                                                    {{ $meeting->duration_minutes }} min
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $userParticipant = $meeting->participants->where('user_id', auth()->id())->first();
                                                @endphp
                                                @if($userParticipant)
                                                    @if($userParticipant->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($userParticipant->status == 'waiting')
                                                        <span class="badge bg-warning">Waiting</span>
                                                    @elseif($userParticipant->status == 'kicked')
                                                        <span class="badge bg-danger">Kicked</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not Joined</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('meeting.join', $meeting->code) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-sign-in-alt"></i> Join
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-video fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No meetings found</h5>
                            <p class="text-muted">Create your first meeting to get started!</p>
                            <a href="{{ route('meeting.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Meeting
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
