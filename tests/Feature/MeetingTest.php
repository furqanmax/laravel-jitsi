<?php

namespace VcMeet\Jitsi\Tests\Feature;

use VcMeet\Jitsi\Tests\TestCase;
use VcMeet\Jitsi\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeetingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_meeting()
    {
        $meeting = Meeting::create([
            'code' => 'TEST123',
            'host_id' => 1,
        ]);

        $this->assertInstanceOf(Meeting::class, $meeting);
        $this->assertEquals('TEST123', $meeting->code);
        $this->assertEquals(1, $meeting->host_id);
    }

    /** @test */
    public function it_has_participants_relationship()
    {
        $meeting = Meeting::create([
            'code' => 'TEST123',
            'host_id' => 1,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $meeting->participants());
    }

    /** @test */
    public function it_can_access_meeting_create_page()
    {
        $response = $this->get('/meeting/create');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_meeting()
    {
        $response = $this->post('/meeting/store');
        
        $response->assertRedirect();
        $this->assertDatabaseHas('meetings', [
            'host_id' => 1, // Default test user
        ]);
    }

    /** @test */
    public function it_can_list_meetings()
    {
        Meeting::create([
            'code' => 'TEST123',
            'host_id' => 1,
        ]);

        $response = $this->get('/meetings');
        
        $response->assertStatus(200);
        $response->assertSee('TEST123');
    }
}
