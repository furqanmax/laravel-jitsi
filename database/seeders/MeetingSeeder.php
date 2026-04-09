<?php

namespace VcMeet\Jitsi\Database\Seeders;

use VcMeet\Jitsi\Models\Meeting;
use VcMeet\Jitsi\Models\MeetingParticipant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
        // Test Meeting 1: Active meeting
        $meeting1 = Meeting::create([
            'code' => 'TEST123',
            'host_id' => 1,
            'start_time' => Carbon::now()->subMinutes(30), // Started 30 mins ago
            'duration_minutes' => 60, // 1 hour duration
        ]);

        // Test Meeting 2: Future meeting
        $meeting2 = Meeting::create([
            'code' => 'TEST456',
            'host_id' => 1,
            'start_time' => Carbon::now()->addMinutes(15), // Starts in 15 mins
            'duration_minutes' => 45, // 45 min duration
        ]);

        // Test Meeting 3: Expired meeting
        $meeting3 = Meeting::create([
            'code' => 'TEST789',
            'host_id' => 1,
            'start_time' => Carbon::now()->subHours(2), // Started 2 hours ago
            'duration_minutes' => 30, // 30 min duration (expired)
        ]);

        // Add approved participants for testing
        foreach ([$meeting1, $meeting2, $meeting3] as $meeting) {
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id' => 1, // Assuming user ID 1 exists
                'status' => 'approved'
            ]);
        }
    }
}
