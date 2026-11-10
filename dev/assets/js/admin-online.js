(() => {
    const { ajaxUrl, nonce } = dcAdminOnline;

    const activityTimeout = 600000;
    const pingInterval = 60000;

    let lastActivity = Date.now();
    let lastPing = 0;

    const ping = async () => {
        if (document.hidden) return;
        if (Date.now() - lastActivity > activityTimeout) return;
        if (Date.now() - lastPing < pingInterval) return;

        lastPing = Date.now();

        const params = new URLSearchParams({
            action: 'dc_online_ping',
            nonce
        });

        try {
            const response = await fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const result = await response.json();

            if (!result.success) {
                console.error('Admin activity ping failed:', result);
            }

        } catch (error) {
            console.error('Admin activity ping failed:', error);
        }
    };

    const recordActivity = () => {
        lastActivity = Date.now();
        ping();
    };

    // Track administrator activity
    ['pointerdown', 'keydown', 'input', 'scroll', 'touchstart'].forEach(event => {
        document.addEventListener(event, recordActivity, { passive: true });
    });

    // Check again when returning to the admin tab
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            recordActivity();
        }
    });

    // Initial ping and periodic heartbeat
    ping();
    setInterval(ping, pingInterval);

})();