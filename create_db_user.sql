-- Script para crear usuario de base de datos para Martinez Construction System
-- Ejecutar como administrador de MySQL

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS martinez_construction CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario específico para el sistema Martinez
CREATE USER IF NOT EXISTS 'martinez_user'@'localhost' IDENTIFIED BY 'Martinez2024!';
CREATE USER IF NOT EXISTS 'martinez_user'@'127.0.0.1' IDENTIFIED BY 'Martinez2024!';
CREATE USER IF NOT EXISTS 'martinez_user'@'%' IDENTIFIED BY 'Martinez2024!';

-- Otorgar todos los privilegios en la base de datos martinez_construction
GRANT ALL PRIVILEGES ON martinez_construction.* TO 'martinez_user'@'localhost';
GRANT ALL PRIVILEGES ON martinez_construction.* TO 'martinez_user'@'127.0.0.1';
GRANT ALL PRIVILEGES ON martinez_construction.* TO 'martinez_user'@'%';

-- Aplicar cambios
FLUSH PRIVILEGES;

-- Verificar que el usuario fue creado correctamente
SELECT User, Host FROM mysql.user WHERE User = 'martinez_user';

-- Mostrar privilegios del usuario
SHOW GRANTS FOR 'martinez_user'@'localhost';
