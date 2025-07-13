
CREATE DATABASE IF NOT EXISTS taller_api;

USE taller_api;

CREATE TABLE categorias(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE promociones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    descuento DECIMAL(5, 2) NOT NULL,
    producto_id INT,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);


INSERT INTO categorias (nombre) VALUES
('Ropa'),
('Calzado'),
('Accesorios');

INSERT INTO productos (nombre, precio, categoria_id) VALUES
('Polo', 50000.00, 1),    
('Zapatos', 120000.00, 2), 
('Gorra', 30000.00, 3),  
('Jean', 80000.00, 1),  
('Sandalias', 45000.00, 2); 

INSERT INTO promociones (descripcion, descuento, producto_id) VALUES
('Promocion mega exclusiva para clientes bien fieles', 99.00, 2),
('Promocion por lastima', 5.00, 4),
('Promocion por palanca con el dueño', 30.00, 1);