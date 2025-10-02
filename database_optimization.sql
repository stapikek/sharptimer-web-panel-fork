-- Database Optimization
-- Creating indexes for performance improvement

-- Index for searching by SteamID (main query)
CREATE INDEX IF NOT EXISTS idx_steamid ON PlayerRecords(SteamID);

-- Index for searching by MapName (main query)
CREATE INDEX IF NOT EXISTS idx_mapname ON PlayerRecords(MapName);

-- Composite index for searching best records by map
CREATE INDEX IF NOT EXISTS idx_mapname_timer ON PlayerRecords(MapName, TimerTicks);

-- Composite index for searching player records by map
CREATE INDEX IF NOT EXISTS idx_steamid_mapname ON PlayerRecords(SteamID, MapName);

-- Index for searching by player name
CREATE INDEX IF NOT EXISTS idx_playername ON PlayerRecords(PlayerName);

-- Composite index for searching by SteamID and time (for sorting)
CREATE INDEX IF NOT EXISTS idx_steamid_timer ON PlayerRecords(SteamID, TimerTicks);

-- Index for searching by time (for record sorting)
CREATE INDEX IF NOT EXISTS idx_timer ON PlayerRecords(TimerTicks);

-- Index for searching by date (if using UnixStamp)
CREATE INDEX IF NOT EXISTS idx_unixstamp ON PlayerRecords(UnixStamp);

-- Composite index for complex queries with grouping
CREATE INDEX IF NOT EXISTS idx_steamid_mapname_timer ON PlayerRecords(SteamID, MapName, TimerTicks);

-- Table optimization for fast searching
-- Table analysis for statistics update
ANALYZE TABLE PlayerRecords;

-- MySQL settings for optimization
-- Increasing buffer size for queries
SET SESSION sort_buffer_size = 2097152; -- 2MB
SET SESSION read_buffer_size = 1048576;  -- 1MB
SET SESSION read_rnd_buffer_size = 1048576; -- 1MB

-- Enabling query caching
SET SESSION query_cache_type = ON;
SET SESSION query_cache_size = 67108864; -- 64MB

-- Optimization for InnoDB
SET SESSION innodb_buffer_pool_size = 134217728; -- 128MB
SET SESSION innodb_log_file_size = 268435456; -- 256MB
SET SESSION innodb_flush_log_at_trx_commit = 2;

-- Creating views for frequently used queries
CREATE OR REPLACE VIEW v_player_stats AS
SELECT 
    SteamID,
    PlayerName,
    COUNT(DISTINCT MapName) as maps_completed,
    COUNT(*) as total_records,
    MIN(TimerTicks) as best_time_ticks,
    AVG(TimerTicks) as avg_time_ticks
FROM PlayerRecords 
GROUP BY SteamID, PlayerName;

CREATE OR REPLACE VIEW v_map_stats AS
SELECT 
    MapName,
    COUNT(DISTINCT SteamID) as unique_players,
    COUNT(*) as total_records,
    MIN(TimerTicks) as best_time_ticks,
    AVG(TimerTicks) as avg_time_ticks
FROM PlayerRecords 
GROUP BY MapName;

-- Creating procedure for cleaning old records (optional)
DELIMITER //
CREATE PROCEDURE CleanOldRecords(IN days_old INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE affected_rows INT DEFAULT 0;
    
    -- Delete records older than specified number of days
    DELETE FROM PlayerRecords 
    WHERE UnixStamp < (UNIX_TIMESTAMP() - (days_old * 86400));
    
    SET affected_rows = ROW_COUNT();
    
    -- Optimize table after deletion
    OPTIMIZE TABLE PlayerRecords;
    
    SELECT CONCAT('Deleted ', affected_rows, ' old records') as result;
END //
DELIMITER ;

-- Creating function for fast player search
DELIMITER //
CREATE FUNCTION GetPlayerBestTime(p_steamid VARCHAR(17), p_mapname VARCHAR(100))
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE best_time VARCHAR(20) DEFAULT NULL;
    
    SELECT FormattedTime INTO best_time
    FROM PlayerRecords 
    WHERE SteamID = p_steamid AND MapName = p_mapname
    ORDER BY TimerTicks ASC 
    LIMIT 1;
    
    RETURN IFNULL(best_time, 'N/A');
END //
DELIMITER ;

-- Creating trigger for automatic statistics update
DELIMITER //
CREATE TRIGGER tr_player_records_after_insert
AFTER INSERT ON PlayerRecords
FOR EACH ROW
BEGIN
    -- Add logic for cache or statistics update here
    -- For example, updating player record counter
    INSERT IGNORE INTO player_cache (SteamID, PlayerName, last_updated)
    VALUES (NEW.SteamID, NEW.PlayerName, NOW())
    ON DUPLICATE KEY UPDATE 
        last_updated = NOW(),
        PlayerName = NEW.PlayerName;
END //
DELIMITER ;

-- Creating cache table (optional)
CREATE TABLE IF NOT EXISTS player_cache (
    SteamID VARCHAR(17) PRIMARY KEY,
    PlayerName VARCHAR(100),
    total_records INT DEFAULT 0,
    maps_completed INT DEFAULT 0,
    best_time_ticks BIGINT DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_last_updated (last_updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creating map statistics cache table
CREATE TABLE IF NOT EXISTS map_cache (
    MapName VARCHAR(100) PRIMARY KEY,
    unique_players INT DEFAULT 0,
    total_records INT DEFAULT 0,
    best_time_ticks BIGINT DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_last_updated (last_updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;