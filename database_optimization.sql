-- Оптимизация базы данных
-- Создание индексов для улучшения производительности

-- Индекс для поиска по SteamID (основной запрос)
CREATE INDEX IF NOT EXISTS idx_steamid ON PlayerRecords(SteamID);

-- Индекс для поиска по MapName (основной запрос)
CREATE INDEX IF NOT EXISTS idx_mapname ON PlayerRecords(MapName);

-- Составной индекс для поиска лучших рекордов по карте
CREATE INDEX IF NOT EXISTS idx_mapname_timer ON PlayerRecords(MapName, TimerTicks);

-- Составной индекс для поиска рекордов игрока по карте
CREATE INDEX IF NOT EXISTS idx_steamid_mapname ON PlayerRecords(SteamID, MapName);

-- Индекс для поиска по имени игрока
CREATE INDEX IF NOT EXISTS idx_playername ON PlayerRecords(PlayerName);

-- Составной индекс для поиска по SteamID и времени (для сортировки)
CREATE INDEX IF NOT EXISTS idx_steamid_timer ON PlayerRecords(SteamID, TimerTicks);

-- Индекс для поиска по времени (для сортировки рекордов)
CREATE INDEX IF NOT EXISTS idx_timer ON PlayerRecords(TimerTicks);

-- Индекс для поиска по дате (если используется UnixStamp)
CREATE INDEX IF NOT EXISTS idx_unixstamp ON PlayerRecords(UnixStamp);

-- Составной индекс для сложных запросов с группировкой
CREATE INDEX IF NOT EXISTS idx_steamid_mapname_timer ON PlayerRecords(SteamID, MapName, TimerTicks);

-- Оптимизация таблицы для быстрого поиска
-- Анализ таблицы для обновления статистики
ANALYZE TABLE PlayerRecords;

-- Настройки MySQL для оптимизации
-- Увеличиваем размер буфера для запросов
SET SESSION sort_buffer_size = 2097152; -- 2MB
SET SESSION read_buffer_size = 1048576;  -- 1MB
SET SESSION read_rnd_buffer_size = 1048576; -- 1MB

-- Включаем кэширование запросов
SET SESSION query_cache_type = ON;
SET SESSION query_cache_size = 67108864; -- 64MB

-- Оптимизация для InnoDB
SET SESSION innodb_buffer_pool_size = 134217728; -- 128MB
SET SESSION innodb_log_file_size = 268435456; -- 256MB
SET SESSION innodb_flush_log_at_trx_commit = 2;

-- Создание представлений для часто используемых запросов
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

-- Создание процедуры для очистки старых записей (опционально)
DELIMITER //
CREATE PROCEDURE CleanOldRecords(IN days_old INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE affected_rows INT DEFAULT 0;
    
    -- Удаляем записи старше указанного количества дней
    DELETE FROM PlayerRecords 
    WHERE UnixStamp < (UNIX_TIMESTAMP() - (days_old * 86400));
    
    SET affected_rows = ROW_COUNT();
    
    -- Оптимизируем таблицу после удаления
    OPTIMIZE TABLE PlayerRecords;
    
    SELECT CONCAT('Удалено ', affected_rows, ' старых записей') as result;
END //
DELIMITER ;

-- Создание функции для быстрого поиска игроков
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

-- Создание триггера для автоматического обновления статистики
DELIMITER //
CREATE TRIGGER tr_player_records_after_insert
AFTER INSERT ON PlayerRecords
FOR EACH ROW
BEGIN
    -- Здесь можно добавить логику для обновления кэша или статистики
    -- Например, обновление счетчика записей игрока
    INSERT IGNORE INTO player_cache (SteamID, PlayerName, last_updated)
    VALUES (NEW.SteamID, NEW.PlayerName, NOW())
    ON DUPLICATE KEY UPDATE 
        last_updated = NOW(),
        PlayerName = NEW.PlayerName;
END //
DELIMITER ;

-- Создание таблицы для кэширования (опционально)
CREATE TABLE IF NOT EXISTS player_cache (
    SteamID VARCHAR(17) PRIMARY KEY,
    PlayerName VARCHAR(100),
    total_records INT DEFAULT 0,
    maps_completed INT DEFAULT 0,
    best_time_ticks BIGINT DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_last_updated (last_updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Создание таблицы для кэширования статистики карт
CREATE TABLE IF NOT EXISTS map_cache (
    MapName VARCHAR(100) PRIMARY KEY,
    unique_players INT DEFAULT 0,
    total_records INT DEFAULT 0,
    best_time_ticks BIGINT DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_last_updated (last_updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
