# sharptimer-web-panel-fork

A minimalist website for displaying map records and player profiles. Easily deployable on any domain/subfolder, uses relative paths.

## Structure
```
core/
  config.php            # Database, settings, $api_keys and get_api_key()
  includes/
    header.php          # Header, navigation, CSS/JS includes
    locale.php          # RU/EN translations
    path_utils.php      # Universal paths (get*Path)
    security.php        # Login validation
    translations/
assets/
  css/                  # style.css, header.css, records.css, etc.
  js/                   # main.js, header-inline.js, index-inline.js
  images/, dist/, fonts/
pages/
  index.php (in root)   # Main page
  pages/profile.php     # Player profile
  pages/map_records.php # Map records
  pages/rules.php       # Rules
  pages/records.php     # New "Records" section
steam/
  steam_api.php         # Steam helper functions (no authentication)
  steam_avatar.php      # Avatar fetching via Steam API
```

## Setup
1) Database in `core/config.php`:
```php
$servername = "localhost";
$username   = "user";
$password   = "pass";
$dbname     = "surf";
```
2) API keys centralized in `core/config.php`:
```php
$api_keys = [
  'steam' => ''  // Steam Web API Key (optional)
];
```
3) Default language in `core/config.php`:
```php
$default_language = 'ru'; // 'ru' for Russian, 'en' for English
```
4) Language and translations: `core/includes/locale.php`, `translations/ru.php`, `en.php`.

## Features
- Works on any domain/subfolder.
- "Records" section with map filter and "All maps" option
- Maps with `_bonus` are hidden from lists and selections

## Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx (static caching recommended)
- [SharpTimer by deafps](https://github.com/Letaryat/poor-sharptimer/tree/dev) with mysql enabled

<<<<<<< Updated upstream
## Быстрый старт
- Настройте БД и `$api_keys` в `core/config.php`
- Загрузите проект на хостинг/локально
- Откройте `index.php`

## TEST
database_optimization.sql - Оптимизация базы данных. Использовать на свой страх и риск
=======
## Quick Start
- Configure database and `$api_keys` in `core/config.php`
- Upload project to hosting/local environment

## TEST
database_optimization.sql - Database optimization. Use at your own risk.
>>>>>>> Stashed changes
