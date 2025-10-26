<?php
return function (PDO $db) {
    // Create table if not exists (MySQL 5.7 compatible)
    $db->exec("CREATE TABLE IF NOT EXISTS chatbot_flows (
      id INT AUTO_INCREMENT PRIMARY KEY,
      company_id INT NOT NULL,
      name VARCHAR(150) NOT NULL,
      definition JSON NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
};
