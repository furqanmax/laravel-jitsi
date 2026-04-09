<?php

namespace VcMeet\Jitsi\Tests\Unit;

use VcMeet\Jitsi\Tests\TestCase;
use VcMeet\Jitsi\Models\Meeting;
use VcMeet\Jitsi\Models\MeetingParticipant;

class MeetingModelTest extends TestCase
{
    /** @test */
    public function it_uses_correct_fillable_fields()
    {
        $meeting = new Meeting();
        
        $this->assertEquals([
            'code', 
            'host_id', 
            'start_time', 
            'duration_minutes'
        ], $meeting->getFillable());
    }

    /** @test */
    public function it_casts_datetime_fields_correctly()
    {
        $meeting = new Meeting();
        
        $this->assertEquals([
            'start_time' => 'datetime',
            'duration_minutes' => 'integer'
        ], $meeting->getCasts());
    }

    /** @test */
    public function it_has_participants_relationship()
    {
        $meeting = new Meeting();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $meeting->participants());
    }

    /** @test */
    public function it_can_create_meeting_with_all_fields()
    {
        $meeting = Meeting::create([
            'code' => 'TEST123',
            'host_id' => 1,
            'start_time' => now(),
            'duration_minutes' => 60,
        ]);

        $this->assertInstanceOf(Meeting::class, $meeting);
        $this->assertEquals('TEST123', $meeting->code);
        $this->assertEquals(1, $meeting->host_id);
        $this->assertEquals(60, $meeting->duration_minutes);
        $this->assertNotNull($meeting->start_time);
    }
}
