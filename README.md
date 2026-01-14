# sharptimer-web-panel-fork

A modern, responsive web interface for managing CS2 surf server statistics, player records, and leaderboards.

## Features

- **Player Profiles** - View detailed player statistics and personal records
- **Leaderboards** - Browse top players across all maps and game modes
- **Map Records** - Track best times on all available maps
- **Live Search** - Search players by name or SteamID
- **Multi-Language Support** - English and Russian (easily extensible)
- **Responsive Design** - Mobile-optimized interface (320px - 1920px+)
- **Dark/Light Theme** - User preference saved in browser
- **Server Status** - View active servers and player counts (via GameQ)
- **Steam Integration** - Display player avatars and profiles from Steam

## Quick Start

### Prerequisites

- **PHP 7.4+** (PHP 8.0+ recommended)
- **MySQL/MariaDB 5.7+**
- **Web Server**: Apache 2.4+ or Nginx
- **cURL extension** (for Steam API)

### Installation

1. **Clone or download the project**
```bash
git clone https://github.com/your-repo/decide-surf-server.git
cd decide-surf-server
```

2. **Configure the database**
   - Open `core/config.php`
   - Update database credentials:
   ```php
   $servername = "your_database_host";
   $username = "your_database_user";
   $password = "your_database_password";
   $dbname = "your_database_name";
   ```

3. **Set up Steam API (optional but recommended)**
   - Get your Steam API key: https://steamcommunity.com/dev/apikey
   - Add it to `core/config.php`:
   ```php
   $steam_api_key = 'YOUR_STEAM_API_KEY';
   ```

### Map Configuration

Edit `core/config.php`:

```php
// Default map to display
$defaultmap = "surf_whiteout";

// Enable map categories (SURF, KZ, BHOP, etc.)
$mapdivision = true;

// Default category
$tabopened = "surf";

// Records per page
$limit = 100;
```

### Server List

Configure game servers in `core/config.php`:

```php
$serverq = array(
    0 => array(
        'type' => 'csgo',
        'host' => '192.168.1.1:27015',
        'fakename' => 'Surf Server #1',
        'fakeip' => 'play.example.com:27015'
    ),
    // Add more servers...
);
```

### Social Media Links

Update social links in `core/config.php`:

```php
$social_links = array(
    array(
        'name' => 'Discord',
        'url' => 'https://discord.gg/your-code',
        'icon' => 'fa-brands fa-discord'
    ),
    // Add more platforms...
);
```


Then add to `core/includes/locale.php` and enable in config.

### Mobile Optimization

Responsive breakpoints are configured for:
- **1024px+** - Desktop
- **768px - 1023px** - Tablet
- **480px - 767px** - Mobile
- **360px - 479px** - Small phone
- **< 360px** - Ultra-small screen

### Important Security Notes

**NEVER commit `core/config.php` with real credentials to version control!**

Use environment variables in production:

```php
$servername = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
```

Set environment variables on your server:

```bash
export DB_HOST="your_host"
export DB_USER="your_user"
export DB_PASS="your_password"
```

## API Endpoints

### GET /api/set_lang.php
Change user language preference
```bash
curl -X POST http://your-domain/api/set_lang.php -d "lang=en"
```

### GET /assets/ajax/livesearch.php
Search players
```bash
curl "http://your-domain/assets/ajax/livesearch.php?q=player_name"
```

### GET /assets/ajax/selection.php
Get map leaderboard
```bash
curl "http://your-domain/assets/ajax/selection.php?id=surf_whiteout"
```

## Fork

This project is a fork of the original [SharpTimer Web Panel](https://github.com/Letaryat/sharptimer-web-panel).

---

**Fork created with ❤️ for CS2 community by stapi**
