-- Crear base de datos
CREATE DATABASE IF NOT EXISTS vulnmart;
USE vulnmart;

-- Tabla de usuarios (¡VULNERABLE!)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL, -- ¡VULNERABILIDAD: Contraseñas en texto plano!
    email VARCHAR(100),
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de sesiones
CREATE TABLE sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    data TEXT, -- ¡VULNERABILIDAD: Datos serializados sin validación!
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de logs
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos iniciales
-- ¡VULNERABILIDAD: Credenciales predeterminadas débiles!
INSERT INTO users (username, password, email, is_admin) VALUES
('admin', 'admin123', 'admin@vulnmart.com', TRUE),
('alice', 'password123', 'alice@example.com', FALSE),
('bob', 'bob123', 'bob@example.com', FALSE);

INSERT INTO products (name, description, price, image_path) VALUES
('Laptop Gaming', 'Potente laptop para gaming', 1200.00, 'uploads/laptop.jpg'),
('Smartphone Pro', 'Teléfono inteligente de última generación', 800.00, 'uploads/phone.jpg'),
('Auriculares Wireless', 'Auriculares con cancelación de ruido', 150.00, 'uploads/headphones.jpg');

-- ¡VULNERABILIDAD: Credenciales codificadas en logs!
INSERT INTO logs (user_id, action, ip_address) VALUES
(1, 'Login exitoso', '192.168.1.1'),
(2, 'Búsqueda: laptop', '192.168.1.2');