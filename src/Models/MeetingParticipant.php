<?php

namespace VcMeet\Jitsi\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model {
    protected $fillable = ['meeting_id', 'user_id', 'status'];

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function meeting() {
        return $this->belongsTo(\VcMeet\Jitsi\Models\Meeting::class);
    }
}
