# sharptimer-web-panel-fork

Web panel for displaying player records of various game modes: SURF, KZ, BHOP and others.

## Table of Contents

- [Description](#description)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [API](#api)
- [Debug System](#debug-system)
- [Database](#database)
- [Multilingual Support](#multilingual-support)
- [Development](#development)
- [License](#license)

## Description

SharpTimer Web Panel Fork is a modern web panel for displaying and managing player records in CS2. The system supports various game modes and provides a convenient interface for viewing statistics, leaderboards, and player profiles.

## Features

### Core Functions
- **Leaderboard** - display of best records by maps
- **Player Profiles** - detailed statistics for each player
- **Map Categorization** - separation by types (SURF, KZ, BHOP, Other)
- **Player Search** - search by nickname or SteamID64
- **Multilingual Support** - Russian and English language support
- **Responsive Design** - proper display on all devices

### Additional Features
- **Steam Integration** - display of player avatars and status
- **Server Monitoring** - display of game server status
- **Dark/Light Theme** - switching between themes
- **Debug System** - built-in tools for developers
- **Performance Optimization** - caching and query optimization

## Requirements

### System Requirements
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher / MariaDB 10.2 or higher
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP Extensions**:
  - mysqli
  - json
  - curl
  - mbstring
- [SharpTimer by Letaryat](https://github.com/Letaryat/poor-sharptimer/tree/dev) with mysql enabled

### Recommended Settings
- **PHP Memory**: minimum 128MB
- **Execution Time**: 30 seconds
- **Upload File Size**: 10MB

## Installation

### 1. Clone Repository
```bash
git clone https://github.com/stapikek/sharptimer-web-panel-fork.git
cd sharptimer-web-panel-fork
```

### 2. Web Server Setup
Configure a virtual host pointing to the project root directory.

### 3. Database Setup (Not Tested)
```sql
-- Create database
CREATE DATABASE sharptimer_web_panel;

-- Import table structure
-- (database_optimization.sql contains optimized structure)
```

### 4. Configuration
Edit the configuration file:
```bash
cp core/config.php
```

### 5. Permissions
```bash
chmod 755 cache/
chmod 644 core/config.php
```

## Configuration

### Main Settings (`core/config.php`)

#### Database
```php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "sharptimer_web_panel";
```

#### Page Settings
```php
$pagetitle = "Your Server Name";
$footerdesc = "Your server description";
$defaultmap = "surf_whiteout"; // Default map
$limit = 100; // Number of records per page
```

#### Map Division
```php
$mapdivision = true; // Enable map type separation
$tabopened = "surf"; // Default open tab
```

#### Language Settings
```php
$default_language = 'en'; // 'ru' or 'en'
```

#### Debug
```php
$debug_enabled = false; // Enable/disable debug
```

### Social Networks
```php
$social_links = array(
    array(
        'name' => 'Discord',
        'url' => 'https://discord.gg/your-server',
        'icon' => 'fa-brands fa-discord'
    ),
    // Add other networks
);
```

### Server Monitoring
```php
$serverlist = true; // Enable server display

$serverq = array(
    0 => array(
        'type' => 'csgo',
        'host' => 'your.server.ip:port',
        'fakename' => 'Server Name',
        'fakeip' => 'your.domain.com:port'
    ),
    // Add other servers
);
```

## Project Structure

```
sharptimer-web-panel-fork/
├── api/                    # API endpoints
│   └── player.php            # API for getting player data
├── assets/                # Static resources
│   ├── css/              # Styles
│   ├── js/               # JavaScript files
│   ├── images/           # Images and icons
│   └── GameQ/            # Server monitoring library
├── cache/                 # Cache files
├── core/                  # Main logic
│   ├── config.php           # Configuration
│   ├── includes/         # Helper files
│   │   ├── header.php       # Page header
│   │   ├── locale.php       # Localization system
│   │   ├── security.php     # Security functions
│   │   └── translations/ # Translations
│   └── cron/             # Cron jobs
├── pages/                 # Website pages
│   ├── profile.php          # Player profile
│   ├── records.php          # Records page
│   ├── map_records.php      # Records by map
│   └── rules.php            # Server rules
├── steam/                 # Steam API integration
│   ├── steam_api.php        # Main Steam API functions
│   └── steam_avatar.php     # Avatar retrieval
├── Documentation/         # Documentation
│   ├── DEBUG_GUIDE_EN.md    # Debug guide (EN)
│   └── DEBUG_GUIDE_RU.md    # Debug guide (RU)
├── index.php                # Main page
├── 404.php                  # 404 error page
├── database_optimization.sql # Database optimization
└── README.md                # This file
```

## API

### Get Player Data
**Endpoint**: `/api/player.php?steamid={STEAMID64}`

**Parameters**:
- `steamid` (required) - Player's SteamID64

**Response**:
```json
{
  "success": true,
  "language": "en",
  "translations": { ... },
  "player": {
    "steamid": "76561198000000000",
    "name": "PlayerName",
    "steam_formats": { ... },
    "steam_profile": { ... },
    "steam_status": { ... }
  },
  "statistics": {
    "total_records": 150,
    "maps_completed": 25,
    "best_time": "1:23.456",
    "average_time": "2:15.789"
  },
  "best_records": [ ... ],
  "maps": [ ... ]
}
```

### Response Codes
- `200` - Successful request
- `400` - Invalid SteamID
- `404` - Player not found
- `500` - Internal server error

## Debug System

The project includes a built-in debug system for monitoring performance and diagnosing issues.

### Enable Debug
```php
// In core/config.php
$debug_enabled = true;
```

### Available Functions
- `debug_log($message, $type)` - Main logging function
- `debug_info($message)` - Informational messages
- `debug_warn($message)` - Warnings
- `debug_error($message)` - Errors
- `debug_sql($query, $params)` - SQL query logging
- `debug_performance($start_time, $operation)` - Performance measurement

### Testing
Open `debug_test.php` in browser to test all debug functions.

Detailed documentation available in files:
- [DEBUG_GUIDE_EN.md](Documentation/DEBUG_GUIDE_EN.md)
- [DEBUG_GUIDE_RU.md](Documentation/DEBUG_GUIDE_RU.md)

## Database

### Main Table
```sql
CREATE TABLE PlayerRecords (
    SteamID VARCHAR(17),
    PlayerName VARCHAR(100),
    MapName VARCHAR(100),
    FormattedTime VARCHAR(20),
    TimerTicks BIGINT,
    UnixStamp INT,
    -- other fields...
);
```

### Optimization (Not Tested)
File `database_optimization.sql` contains:
- Indexes for query acceleration
- Views for frequently used queries
- Procedures for cleaning old records
- MySQL optimization settings

### Recommended Indexes
- `idx_steamid` - for SteamID search
- `idx_mapname` - for map search
- `idx_mapname_timer` - for record sorting
- `idx_steamid_mapname` - for player records by map search

## Multilingual Support

The system supports Russian and English languages. Translations are stored in files:
- `core/includes/translations/ru.php` - Russian
- `core/includes/translations/en.php` - English

### Adding New Language
1. Create file `core/includes/translations/{lang}.php`
2. Copy structure from existing file
3. Translate all strings
4. Update `getCurrentLanguage()` function in `locale.php`

### Using Translations
```php
echo t('player'); // Will output "Player" or "Игрок"
```

## Development

### Local Development
1. Install local server (XAMPP, WAMP, MAMP)
2. Clone repository to web server folder
3. Configure database
4. Enable debug in configuration

### Code Structure
- **MVC Pattern** - separation of logic, view, and data
- **Security** - protection against SQL injection, XSS
- **Performance** - caching, query optimization
- **Debug** - built-in logging system

### Recommendations
- Use prepared statements for SQL queries
- Validate all user data
- Log important operations
- Test on different browsers and devices

## Fork

This project is a fork of the original SharpTimer Web Panel.

---

**Fork created with ❤️ for CS2 community**
