-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS ecohotel;
USE ecohotel;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de tipos de habitación
CREATE TABLE tipos_habitacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    precio_noche DECIMAL(10, 2) NOT NULL,
    capacidad INT NOT NULL,
    imagen VARCHAR(255),
    slug VARCHAR(50) UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de habitaciones (instancias físicas)
CREATE TABLE habitaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_id INT NOT NULL,
    numero VARCHAR(10) NOT NULL UNIQUE,
    estado ENUM('disponible', 'ocupada', 'mantenimiento') DEFAULT 'disponible',
    FOREIGN KEY (tipo_id) REFERENCES tipos_habitacion(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de reservas
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    huespedes INT NOT NULL,
    precio_total DECIMAL(12, 2) NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada', 'completada') DEFAULT 'pendiente',
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    comentarios TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de servicios adicionales
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    disponible BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de relación entre reservas y servicios
CREATE TABLE reserva_servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    servicio_id INT NOT NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES servicios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    monto DECIMAL(12, 2) NOT NULL,
    metodo ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
    estado ENUM('pendiente', 'completado', 'rechazado', 'reembolsado') DEFAULT 'pendiente',
    fecha_pago DATETIME,
    transaccion_id VARCHAR(100),
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de contactos/mensajes
CREATE TABLE mensajes_contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    mensaje TEXT NOT NULL,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT FALSE,
    respuesta TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de configuración del hotel
CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_hotel VARCHAR(100) NOT NULL DEFAULT 'EcoHotel Doradal',
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    politica_cancelacion TEXT,
    check_in TIME DEFAULT '14:00:00',
    check_out TIME DEFAULT '12:00:00',
    logo VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos iniciales
INSERT INTO tipos_habitacion (nombre, descripcion, precio_noche, capacidad, imagen, slug) VALUES
('Habitación Estándar', 'Espaciosa habitación con vista al jardín y baño privado.', 120000, 2, 'habitacion-estandar.jpg', 'estandar'),
('Habitación Doble', 'Ideal para parejas, con cama doble y balcón privado.', 160000, 2, 'habitacion-doble.jpg', 'doble'),
('Suite Eco', 'Suite de lujo con terraza y vista panorámica a la naturaleza.', 250000, 4, 'suite-eco.jpg', 'suite');

-- Insertar habitaciones físicas
INSERT INTO habitaciones (tipo_id, numero, estado) VALUES
(1, '101', 'disponible'),
(1, '102', 'disponible'),
(1, '103', 'disponible'),
(2, '201', 'disponible'),
(2, '202', 'disponible'),
(3, '301', 'disponible'),
(3, '302', 'disponible');

-- Insertar servicios adicionales
INSERT INTO servicios (nombre, descripcion, precio) VALUES
('Desayuno Buffet', 'Desayuno completo con opciones locales e internacionales', 25000),
('Cena Romántica', 'Cena especial para parejas en ubicación privada', 120000),
('Tour Ecológico', 'Recorrido guiado por los senderos naturales del hotel', 50000),
('Masaje Relajante', 'Sesión de 60 minutos de masaje terapéutico', 80000),
('Lavandería Express', 'Servicio de lavandería con entrega en 4 horas', 30000);

-- Insertar configuración inicial
INSERT INTO configuracion (nombre_hotel, direccion, telefono, email, politica_cancelacion) VALUES
('EcoHotel Doradal', 'Doradal, Antioquia, Colombia', '+57 300 123 4567', 'contacto@ecohoteldoradal.com', 'Cancelaciones con 48 horas de anticipación para reembolso completo.');

-- Crear usuario administrador (contraseña: Admin1234)
INSERT INTO usuarios (nombre, email, password, telefono, rol) VALUES
('Administrador', 'admin@ecohoteldoradal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+57 310 987 6543', 'admin');