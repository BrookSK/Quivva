-- Quivva database schema and seed

CREATE TABLE IF NOT EXISTS companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  plan VARCHAR(50) DEFAULT 'free',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(50) DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pipelines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  position INT DEFAULT 0,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  user_id INT NULL,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(40),
  email VARCHAR(150),
  stage VARCHAR(60) DEFAULT 'Novo',
  source VARCHAR(60),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lead_id INT NOT NULL,
  sender ENUM('lead','system','user') NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS automations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  trigger_event VARCHAR(120) NOT NULL,
  action VARCHAR(120) NOT NULL,
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data
INSERT INTO companies (name, plan) VALUES ('Acme Ltd', 'free');
INSERT INTO users (company_id, name, email, password_hash, role)
VALUES (1, 'Admin', 'admin@acme.test', '$2y$10$abcdefghijklmnopqrstuvCwO7q5i7RzHh0YF9Qn1x5x7rGxX7P1e9e', 'admin');
INSERT INTO pipelines (company_id, name, position) VALUES (1, 'Padrão', 1);
INSERT INTO leads (company_id, user_id, name, phone, email, stage, source)
VALUES (1, 1, 'João da Silva', '+55 11 90000-0000', 'joao@example.com', 'Novo', 'Site');
INSERT INTO messages (lead_id, sender, message) VALUES (1, 'lead', 'Oi, gostaria de saber o preço.');

-- Chatbot flows
CREATE TABLE IF NOT EXISTS chatbot_flows (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  definition JSON NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO chatbot_flows (company_id, name, definition) VALUES
(1, 'Boas-vindas', JSON_OBJECT(
  'blocks', JSON_ARRAY(
    JSON_OBJECT('type','text','text','Olá {{nome}}, seja bem-vindo ao nosso atendimento!'),
    JSON_OBJECT('type','question','variable','produto','text','Qual produto você tem interesse?')
  )
));
