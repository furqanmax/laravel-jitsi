<?php

namespace VcMeet\Jitsi\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model {
    protected $fillable = [
        'code', 
        'host_id', 
        'start_time', 
        'duration_minutes'
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'duration_minutes' => 'integer'
    ];
    
    public function participants() {
        return $this->hasMany(\VcMeet\Jitsi\Models\MeetingParticipant::class);
    }
}
