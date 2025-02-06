CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL, /*Aquí podriamos añadir cositas de seguridad*/
    password VARCHAR(100) NOT NULL,
    clave_asociada INT(6),
    estado BOOLEAN DEFAULT FALSE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);