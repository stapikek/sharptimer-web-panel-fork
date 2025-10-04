function toggleLanguageDropdown(){const dropdown=document.getElementById('languageDropdown');dropdown.classList.toggle('show');}
document.addEventListener('click',function(event){const dropdown=document.getElementById('languageDropdown');const button=document.querySelector('.language-button');if(dropdown&&button&&!dropdown.contains(event.target)&&!button.contains(event.target)){dropdown.classList.remove('show');}});

// Theme switcher functionality
function toggleTheme() {
    const html = document.documentElement;
    const themeIcon = document.getElementById('theme-icon');
    const currentTheme = html.getAttribute('data-theme');
    
    if (currentTheme === 'light') {
        html.setAttribute('data-theme', 'dark');
        themeIcon.className = 'fa-solid fa-moon';
        localStorage.setItem('theme', 'dark');
    } else {
        html.setAttribute('data-theme', 'light');
        themeIcon.className = 'fa-solid fa-sun';
        localStorage.setItem('theme', 'light');
    }
}

// Load saved theme on page load (оптимизировано для предотвращения мигания)
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    const themeIcon = document.getElementById('theme-icon');
    
    // Тема уже установлена в head, просто обновляем иконку
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
    if (themeIcon) {
        themeIcon.className = currentTheme === 'light' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }
});

function loadSteamAvatarInline(steamid){console.log('Аватарка уже загружена для SteamID:',steamid);} 
function showMapRecordsInline(steamid,mapName){window.location.href=`/pages/map_records.php?steamid=${steamid}&map=${encodeURIComponent(mapName)}`;}
document.addEventListener('DOMContentLoaded',function(){var el=document.getElementById('player-avatar');});

// Index page handlers
function bindIndexHandlers(){
    if (typeof $ === 'function'){
        $('.selector').on('click', function () {
            var data_id = $(this).data('id');
            $.ajax({ url: '/assets/ajax/selection.php', type: 'POST', data: { id: data_id }, dataType: 'text', success: function (data) { $('.players').html(data); }, error: function () { $('.players').html(''); alert('Error Loading'); } });
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
                    $.ajax({ url: '/assets/ajax/livesearch.php', type: 'POST', data: { input: input }, dataType: 'text', success: function (data) { $('.players').html(data); }, error: function () { $('.players').html(''); alert('Error Loading'); } });
                } else { }
            });
        });
    }
}

