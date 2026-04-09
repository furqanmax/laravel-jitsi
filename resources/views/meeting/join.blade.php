@extends('layouts.app')

@section('content')
<div class="">
   

        <div id="jitsi"></div>

        <div id="meeting-timer" style="position:fixed;top:16px;right:16px;z-index:9999;background:rgba(0,0,0,0.75);color:#fff;padding:10px 18px;border-radius:8px;font-size:18px;font-family:monospace;">
            &#x23f1; <span id="timer-display">Loading...</span>
        </div>

        <script src="https://meet.eshare.ai/external_api.js"></script>
        <script>
            const MEETING_CODE  = "{{ $meeting->code }}";
            const TIME_API_URL  = `/meeting/${MEETING_CODE}/time-remaining`;
            const REDIRECT_URL  = "/";

            // &#8212;&#8212; Jitsi setup &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
            const api = new JitsiMeetExternalAPI("meet.eshare.ai", {
                roomName : MEETING_CODE,
                width    : "100%",
                height   : "100vh",
                parentNode: document.querySelector('#jitsi'),
                userInfo : { displayName: "{{ Auth::user()->name }}" },
                configOverwrite: {
                    prejoinPageEnabled : false,
                    prejoinConfig      : { enabled: false },
                    disableDeepLinking : true,
                    startWithAudioMuted: false,
                    startWithVideoMuted: false,
                },
                interfaceConfigOverwrite: {
                    SHOW_CHROME_EXTENSION_BANNER: false,
                    TOOLBAR_BUTTONS: ['microphone', 'camera', 'hangup', 'chat'],
                },
            });

            // &#8212;&#8212; Timer driven entirely by server &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
            const timerDisplay = document.getElementById('timer-display');
            const timerBox     = document.getElementById('meeting-timer');
            let   timerInterval;
            let   meetingEnded = false;

            function pad(n) { return String(n).padStart(2, '0'); }

            function endMeeting(label) {
                if (meetingEnded) return;
                meetingEnded = true;
                clearInterval(timerInterval);
                api.executeCommand('hangup');
                timerDisplay.textContent = label;
                setTimeout(() => window.location.href = REDIRECT_URL, 2000);
            }

            async function syncWithServer() {
                if (meetingEnded) return;

                let data;
                try {
                    const res = await fetch(TIME_API_URL, {
                        headers: {
                            'Accept'           : 'application/json',
                            'X-Requested-With' : 'XMLHttpRequest',
                        }
                    });

                    // Kicked / unauthorized mid-meeting
                    if (res.status === 403) {
                        endMeeting('&#x26d4; Removed from meeting&#8230;');
                        return;
                    }

                    data = await res.json();
                } catch (err) {
                    // Network blip &#8212; keep displaying last value, retry next tick
                    console.warn('Timer sync failed, retrying&#8230;', err);
                    return;
                }

                const remaining   = data.remaining_seconds;
                const beforeStart = data.before_start_seconds;

                // &#8212;&#8212; Not started yet &#8212;&#8212;
                if (beforeStart > 0) {
                    const wm = Math.floor(beforeStart / 60), ws = beforeStart % 60;
                    timerDisplay.textContent = `Starts in ${pad(wm)}:${pad(ws)}`;
                    timerBox.style.background = 'rgba(0,0,0,0.75)';
                    return;
                }

                // &#8212;&#8212; Expired (server says so) &#8212;&#8212;
                if (remaining <= 0) {
                    endMeeting('&#x23f0; Time is up! Redirecting&#8230;');
                    return;
                }

                // &#8212;&#8212; Count down &#8212;&#8212;
                const hours = Math.floor(remaining / 3600);
                const mins  = Math.floor((remaining % 3600) / 60);
                const secs  = remaining % 60;

                timerDisplay.textContent = hours
                    ? `${pad(hours)}:${pad(mins)}:${pad(secs)} left`
                    : `${pad(mins)}:${pad(secs)} left`;

                // &#8212;&#8212; Warning colours &#8212;&#8212;
                if      (remaining <= 120) timerBox.style.background = 'rgba(220,38,38,0.85)';
                else if (remaining <= 300) timerBox.style.background = 'rgba(234,179,8,0.85)';
                else                       timerBox.style.background = 'rgba(0,0,0,0.75)';
            }

            syncWithServer();
            timerInterval = setInterval(syncWithServer, 1000);

            // &#8212;&#8212; Manual hang-up &#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;&#8212;
            api.addEventListener('readyToClose', () => {
                clearInterval(timerInterval);
                window.location.href = REDIRECT_URL;
            });
        </script>

   
</div>
@endsection
