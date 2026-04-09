<?php

namespace VcMeet\Jitsi\Http\Controllers;

use Illuminate\Http\Request;
use VcMeet\Jitsi\Models\Meeting;
use VcMeet\Jitsi\Models\MeetingParticipant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller {
    public function create() {
        return view('laravel-jitsi::meeting.create');
    }
    
    public function ended($id) {
        return view('laravel-jitsi::meeting.ended', compact('id'));
    }

    public function store(Request $request) {
        $meeting = Meeting::create([
            'code' => Str::random(10),
            'host_id' => auth()->id()
        ]);
        return redirect('/meeting/' . $meeting->code);
    }

    public function join($code) {
        $meeting = Meeting::where('code', $code)->firstOrFail();

        $participant = MeetingParticipant::firstOrCreate([
            'meeting_id' => $meeting->id,
            'user_id' => auth()->id()
        ]);

        $duration_minutes = 20;
        return view('laravel-jitsi::meeting.join', compact('meeting', 'participant', 'duration_minutes'));
    }

    public function approve(Request $request, $code) {
        $participant = MeetingParticipant::where('meeting_id', Meeting::where('code', $code)->value('id'))
            ->where('user_id', $request->user_id)->first();
        if ($participant) {
            $participant->status = 'approved';
            $participant->save();
        }
        return back();
    }

    public function kick(Request $request, $code) {
        $participant = MeetingParticipant::where('meeting_id', Meeting::where('code', $code)->value('id'))
            ->where('user_id', $request->user_id)->first();
        if ($participant) {
            $participant->status = 'kicked';
            $participant->save();
        }
        return back();
    }

    public function index() {
        $meetings = Meeting::with(['participants' => function($query) {
            $query->where('user_id', auth()->id());
        }])->orderBy('created_at', 'desc')->get();
        
        return view('laravel-jitsi::meeting.index', compact('meetings'));
    }

    public function timeRemaining($code)
{
    $meeting = Meeting::where('code', $code)->firstOrFail();

    // Ensure the requester is an approved participant
    $participant = $meeting->participants()
        ->where('user_id', Auth::id())
        ->where('status', 'approved')
        ->first();

    if (!$participant) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $startTime   = $meeting->start_time ?? $meeting->created_at;
    $endTime     = $startTime->copy()->addMinutes($meeting->duration_minutes);
    $now         = now();
    $remaining   = (int) $now->diffInSeconds($endTime, false); // negative if over
    $beforeStart = $now->lt($startTime)
        ? (int) $now->diffInSeconds($startTime, false)
        : 0;

    return response()->json([
        'remaining_seconds' => $remaining,   // negative = expired
        'before_start_seconds' => $beforeStart,
        'server_time' => $now->toIso8601String(),
    ]);
}
}
