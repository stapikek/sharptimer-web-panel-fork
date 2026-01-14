document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadSteamAvatarInline === 'function') {
        loadSteamAvatarInline(window.profileSteamID || '');
    }
});
