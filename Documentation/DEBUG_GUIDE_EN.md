# Debug System Guide

## Overview

Debug system allows tracking code execution, SQL queries, performance and errors through browser console.

## Configuration

### Enable/disable debug

In file `core/config.php`:

```php
// DEBUG CONFIGURATION
$debug_enabled = true;  // true = enabled, false = disabled
```

## Available Functions

### 1. `debug_log($message, $type = 'log')`
Main function for outputting messages to console.

```php
debug_log("User logged in successfully");
debug_log("Error occurred", "error");
```

### 2. `debug_info($message)`
Outputs informational message.

```php
debug_info("Page loaded successfully");
```

### 3. `debug_warn($message)`
Outputs warning.

```php
debug_warn("Invalid input provided");
```

### 4. `debug_error($message)`
Outputs error.

```php
debug_error("Database connection failed");
```

### 5. `debug_sql($query, $params = [])`
Logs SQL queries with parameters.

```php
debug_sql("SELECT * FROM users WHERE id = ?", [123]);
```

### 6. `debug_performance($start_time, $operation = 'Operation')`
Measures operation execution time.

```php
$start = microtime(true);
// ... some code ...
debug_performance($start, "Database query");
```

## Integration Points

### Header.php
- Page load tracking
- Execution time measurement

### Index.php
- SQL queries logging for maps
- Main page load tracking

### API Files
- `api/player.php` - API requests logging
- `assets/ajax/livesearch.php` - search logging
- `assets/ajax/selection.php` - map selection logging

## Testing

### Test Page
Open `debug_test.php` in browser to test all debug functions.

### Console Output
All debug messages are output to browser console with timestamps:

```
[14:30:25] DEBUG: Page load started: /index.php
[14:30:25] DEBUG: SQL: SELECT DISTINCT MapName FROM `PlayerRecords` WHERE MapName LIKE 'SURF%' ORDER BY MapName ASC
[14:30:25] DEBUG: Page load completed in 45.67ms | Memory: 2.34MB | Peak: 3.45MB
```

## Security Features

### JavaScript File Protection
Debug automatically disables for:
- `.js` files
- AJAX requests
- JSON responses

### Content-Type Detection
System checks response headers and doesn't output debug for inappropriate content types.

## Performance Impact

### When Enabled
- Minimal performance impact
- Messages output only to browser console
- Automatic disabling for static files

### When Disabled
- Completely disabled
- Zero performance impact

## Usage Examples

### Basic Debugging
```php
// At function start
debug_log("Function started");

// On error
if (!$result) {
    debug_error("Operation failed");
    return false;
}

// On success
debug_info("Operation completed successfully");
```

### SQL Debugging
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE active = ?");
debug_sql("SELECT * FROM users WHERE active = ?", [1]);
$stmt->bind_param("i", 1);
$stmt->execute();
```

### Performance Monitoring
```php
$start_time = microtime(true);

// ... code execution ...

debug_performance($start_time, "User authentication");
```

## Best Practices

1. **Use appropriate logging level:**
   - `debug_log()` - general information
   - `debug_info()` - important information
   - `debug_warn()` - warnings
   - `debug_error()` - errors

2. **Log SQL queries:**
   - Always use `debug_sql()` for prepared statements
   - Include parameters for debugging

3. **Measure performance:**
   - Use `debug_performance()` for critical operations
   - Monitor memory usage

4. **Disable in production:**
   - Set `$debug_enabled = false` in config.php
   - Or use environment variables

## Troubleshooting

### Debug not working
1. Check `$debug_enabled = true` in config.php
2. Open browser console (F12)
3. Refresh page

### Messages not appearing
1. Make sure debug is enabled
2. Check that code is executing
3. Check console for JavaScript errors

### Performance issues
1. Disable debug in config.php
2. Use `debug_performance()` for monitoring
3. Log only critically important operations
