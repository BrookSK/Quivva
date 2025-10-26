<?php
return function (PDO $db) {
    // Check if column exists (compatible with MySQL 5.7)
    $stmt = $db->prepare("SELECT COUNT(*) AS c FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'leads' AND COLUMN_NAME = 'chat_status' AND TABLE_SCHEMA = DATABASE()");
    $stmt->execute();
    $exists = (int)$stmt->fetchColumn() > 0;
    if (!$exists) {
        $db->exec("ALTER TABLE leads ADD COLUMN chat_status ENUM('aguardando','atendendo','concluido') DEFAULT 'aguardando'");
    }
};
