<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Meeting Duration
    |--------------------------------------------------------------------------
    |
    | The default duration for meetings in minutes.
    |
    */
    'default_duration_minutes' => 60,

    /*
    |--------------------------------------------------------------------------
    | Meeting Code Length
    |--------------------------------------------------------------------------
    |
    | The length of randomly generated meeting codes.
    |
    */
    'code_length' => 10,

    /*
    |--------------------------------------------------------------------------
    | Participant Statuses
    |--------------------------------------------------------------------------
    |
    | Available statuses for meeting participants.
    |
    */
    'participant_statuses' => [
        'waiting',
        'approved', 
        'kicked'
    ],

    /*
    |--------------------------------------------------------------------------
    | Meeting Model
    |--------------------------------------------------------------------------
    |
    | The model class to use for meetings.
    |
    */
    'meeting_model' => \VcMeet\Jitsi\Models\Meeting::class,

    /*
    |--------------------------------------------------------------------------
    | Participant Model
    |--------------------------------------------------------------------------
    |
    | The model class to use for meeting participants.
    |
    */
    'participant_model' => \VcMeet\Jitsi\Models\MeetingParticipant::class,
];
