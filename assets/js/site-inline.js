function getBasePath() {
    const path = window.location.pathname || '/';
    if (path.indexOf('/pages') !== -1 || path.indexOf('/api') !== -1 || path.indexOf('/steam') !== -1 || path.indexOf('/assets') !== -1) {
        return '../';
    }

    return '';
}

function toggleLanguageDropdown(){const dropdown=document.getElementById('languageDropdown');dropdown.classList.toggle('show');}
document.addEventListener('click',function(event){const dropdown=document.getElementById('languageDropdown');const button=document.querySelector('.language-button');if(dropdown&&button&&!dropdown.contains(event.target)&&!button.contains(event.target)){dropdown.classList.remove('show');}});

function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

if (document.addEventListener) {
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (savedTheme && savedTheme !== currentTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
    });
}

function loadSteamAvatarInline(steamid){ }
document.addEventListener('DOMContentLoaded',function(){var el=document.getElementById('player-avatar');});

function removeLangFromUrl(url) {
    try {
        var u = new URL(url, window.location.origin);
        u.searchParams.delete('lang');
        return u.pathname + (u.search ? u.search : '') + (u.hash ? u.hash : '');
    } catch (e) {
        return url;
    }
}

function bindLanguageSwitcher() {
    var elems = document.querySelectorAll('.lang-switch');
    if (!elems || elems.length === 0) return;

    elems.forEach(function(el) {
        el.addEventListener('click', function(ev) {
            ev.preventDefault();
            var lang = el.getAttribute('data-lang');
            if (!lang) return;
            var apiPath = getBasePath() + 'api/set_lang.php';
            fetch(apiPath, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                body: 'lang=' + encodeURIComponent(lang),
                credentials: 'same-origin'
            }).then(function(resp){
                if (!resp.ok) throw new Error('Network error');
                return resp.json();
            }).then(function(data){
                if (data && data.ok) {
                    var newUrl = removeLangFromUrl(window.location.href);
                    window.location.href = newUrl;
                } else {
                    window.location.reload();
                }
            }).catch(function(){
                window.location.reload();
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', function() { try { bindLanguageSwitcher(); } catch(e) { console.error(e); } });

function bindMapSearch() {
    var input = document.getElementById('mapSearch');
    var clearBtn = document.getElementById('mapSearchClear');
    if (!input) return;

    function doFilter() {
        var q = input.value.trim().toLowerCase();
        var items = document.querySelectorAll('.mappeno .selector');
        items.forEach(function(it){
            var name = (it.textContent || it.innerText || '').toLowerCase();
            if (q === '' || name.indexOf(q) !== -1) {
                it.style.display = '';
            } else {
                it.style.display = 'none';
            }
        });
    }

    input.addEventListener('input', doFilter);
    if (clearBtn) {
        clearBtn.addEventListener('click', function(){ input.value = ''; doFilter(); input.focus(); });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    try { bindMapSearch(); } catch(e) { console.error('bindMapSearch error', e); }
});

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

document.addEventListener('DOMContentLoaded', function() {
    try {
        if (document.querySelector('.selector')) {
            bindIndexHandlers();
        }
    } catch (e) {
        console.error('Error binding index handlers:', e);
    }
});

