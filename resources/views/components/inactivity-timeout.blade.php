{{-- Inactivity Timeout: warns at 1:30, logs out at 2:00 of inactivity --}}
<div id="inactivity-overlay"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.55);"
     aria-modal="true" role="dialog" aria-labelledby="inactivity-title">
    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                background:#fff;border-radius:12px;padding:36px 32px;width:360px;
                max-width:90vw;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.25);">
        <!-- Warning icon -->
        <div style="margin-bottom:16px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:52px;height:52px;color:#f59e0b;margin:0 auto;"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <h2 id="inactivity-title"
            style="font-size:1.2rem;font-weight:700;color:#1f2937;margin-bottom:8px;">
            Session Inactivity Warning
        </h2>
        <p style="color:#6b7280;font-size:0.95rem;margin-bottom:20px;">
            You have been inactive. You will be automatically signed out in
        </p>
        <div id="inactivity-countdown"
             style="font-size:2.5rem;font-weight:800;color:#ef4444;margin-bottom:24px;letter-spacing:2px;">
            0:30
        </div>
        <button id="inactivity-stay-btn"
                style="width:100%;padding:10px 0;background:#16a34a;color:#fff;
                       font-weight:600;border:none;border-radius:8px;font-size:1rem;
                       cursor:pointer;transition:background 0.2s;">
            Stay Logged In
        </button>
    </div>
</div>

<script>
(function () {
    const TIMEOUT_MS     = 2 * 60 * 1000;   // 2 minutes total
    const WARN_BEFORE_MS = 30 * 1000;        // show warning 30 s before logout
    const LOGOUT_URL     = '{{ route("sign-out") }}';

    let warnTimer    = null;
    let logoutTimer  = null;
    let countdownInt = null;

    const overlay      = document.getElementById('inactivity-overlay');
    const countdownEl  = document.getElementById('inactivity-countdown');
    const stayBtn      = document.getElementById('inactivity-stay-btn');

    function formatTime(ms) {
        const totalSec = Math.ceil(ms / 1000);
        const m = Math.floor(totalSec / 60);
        const s = totalSec % 60;
        return m + ':' + String(s).padStart(2, '0');
    }

    function showWarning() {
        overlay.style.display = 'block';

        let remaining = WARN_BEFORE_MS;
        countdownEl.textContent = formatTime(remaining);

        countdownInt = setInterval(function () {
            remaining -= 1000;
            if (remaining <= 0) {
                clearInterval(countdownInt);
                countdownEl.textContent = '0:00';
            } else {
                countdownEl.textContent = formatTime(remaining);
            }
        }, 1000);

        logoutTimer = setTimeout(function () {
            window.location.href = LOGOUT_URL;
        }, WARN_BEFORE_MS);
    }

    function hideWarning() {
        overlay.style.display = 'none';
        clearInterval(countdownInt);
        clearTimeout(logoutTimer);
        logoutTimer = null;
        countdownInt = null;
    }

    function resetTimers() {
        clearTimeout(warnTimer);
        clearTimeout(logoutTimer);
        clearInterval(countdownInt);

        if (overlay.style.display !== 'none') {
            hideWarning();
        }

        warnTimer = setTimeout(showWarning, TIMEOUT_MS - WARN_BEFORE_MS);
    }

    // Activity events that reset the timer
    ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function (evt) {
        document.addEventListener(evt, resetTimers, { passive: true });
    });

    // "Stay Logged In" button
    stayBtn.addEventListener('click', function () {
        resetTimers();
    });

    // Start on page load
    resetTimers();
})();
</script>
