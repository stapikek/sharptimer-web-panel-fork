# sharptimer-web-panel-fork

Минималистичный сайт для отображения рекордов карт и профилей игроков. Легко разворачивается в любом домене/подпапке, использует относительные пути.

## Структура
```
core/
  config.php            # БД, настройки, $api_keys и get_api_key()
  includes/
    header.php          # Шапка, навигация, подключения CSS/JS
    locale.php          # RU/EN переводы
    path_utils.php      # Универсальные пути (get*Path)
    security.php        # Валидация входа
    translations/
assets/
  css/                  # style.css, header.css, records.css и др.
  js/                   # main.js, header-inline.js, index-inline.js
  images/, dist/, fonts/
pages/
  index.php (в корне)   # Главная
  pages/profile.php     # Профиль игрока
  pages/map_records.php # Рекорды по карте
  pages/rules.php       # Правила
  pages/records.php     # Новый раздел «Рекорды»
steam/
  steam_api.php         # Вспомогательные функции Steam (без авторизации)
  steam_avatar.php      # Получение аватарок через Steam API
```

## Настройка
1) База данных в `core/config.php`:
```php
$servername = "localhost";
$username   = "user";
$password   = "pass";
$dbname     = "db";
```
2) Ключи API централизованно в `core/config.php`:
```php
$api_keys = [
  'steam' => ''  // Steam Web API Key (по желанию)
];
```
3) Язык и переводы: `core/includes/locale.php`, `translations/ru.php`, `en.php`.

## Особенности
- Работает на любом домене/в подпапке.
- Раздел «Рекорды» с фильтром по картам и опцией «Все карты».
- Карты с `_bonus` скрыты из списков и выборок.

## Требования
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx (желательно включить кеш статики)
- [SharpTimer by deafps](https://github.com/DEAFPS/SharpTimer) with mysql enabled

## Быстрый старт
- Настройте БД и `$api_keys` в `core/config.php`
- Загрузите проект на хостинг/локально
- Откройте `index.php`

## TEST
database_optimization.sql - Оптимизация базы данных. Использовать на свой страх и риск
