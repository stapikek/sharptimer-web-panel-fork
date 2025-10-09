// Get base path for relative URLs
function getBasePath() {
    const path = window.location.pathname;
    const pathSegments = path.split('/').filter(segment => segment);
    
    // If we're in a subfolder (like /surf/), return the subfolder path
    if (pathSegments.length > 0 && pathSegments[0] !== '') {
        return '/' + pathSegments[0] + '/';
    }
    
    return '/';
}

function toggleLanguageDropdown(){const dropdown=document.getElementById('languageDropdown');dropdown.classList.toggle('show');}
document.addEventListener('click',function(event){const dropdown=document.getElementById('languageDropdown');const button=document.querySelector('.language-button');if(dropdown&&button&&!dropdown.contains(event.target)&&!button.contains(event.target)){dropdown.classList.remove('show');}});

// Theme switcher functionality
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    
    if (currentTheme === 'light') {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    } else {
        html.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    }
    
    // Добавляем эффект "щелчка" для лучшего UX
    const themeSwitcher = document.querySelector('.theme-switcher');
    themeSwitcher.style.transform = 'scale(0.95)';
    setTimeout(() => {
        themeSwitcher.style.transform = '';
    }, 150);
}

// Load saved theme on page load (оптимизировано для предотвращения мигания)
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    
    // Тема уже установлена в head, просто убеждаемся что она правильная
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    if (savedTheme && savedTheme !== currentTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }
});

function loadSteamAvatarInline(steamid){console.log('Аватарка уже загружена для SteamID:',steamid);} 
function showMapRecordsInline(steamid,mapName){window.location.href=`${getBasePath()}pages/map_records.php?steamid=${steamid}&map=${encodeURIComponent(mapName)}`;}
document.addEventListener('DOMContentLoaded',function(){var el=document.getElementById('player-avatar');});

// Index page handlers
function bindIndexHandlers(){
    if (typeof $ === 'function'){
        $('.selector').on('click', function () {
            var data_id = $(this).data('id');
            $.ajax({ url: getBasePath() + 'assets/ajax/selection.php', type: 'POST', data: { id: data_id }, dataType: 'text', success: function (data) { $('.players').html(data); }, error: function () { $('.players').html(''); alert('Error Loading'); } });
        });
        $(document).ready(function () {
            var urlParams = new URLSearchParams(window.location.search);
            var mapParam = urlParams.get('map');
            if (mapParam) {
                var mapElement = $('.selector[data-id="' + mapParam + '"]');
                if (mapElement.length > 0) { mapElement.click(); }
            }
            $("#search").keyup(function () {
                var input = $(this).val();
                if (input != "") {
                    $.ajax({ url: getBasePath() + 'assets/ajax/livesearch.php', type: 'POST', data: { input: input }, dataType: 'text', success: function (data) { $('.players').html(data); }, error: function () { $('.players').html(''); alert('Error Loading'); } });
                } else { }
            });
        });
    }
}

