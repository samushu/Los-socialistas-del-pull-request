-- Crear tabla de Categorías
CREATE TABLE Categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL
);

-- Insertar categorías iniciales
INSERT INTO Categoria (nombre) VALUES
('Papeleria'),
('Drogueria'),
('Supermercado'),
('Aseo');

-- Crear tabla de Productos
CREATE TABLE Producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    peso DECIMAL(10,2),
    cantidad INT NOT NULL,
    tipo_empaque ENUM('Carton','Plastico','Otro'),
    precio_unitario DECIMAL(10,2) NOT NULL,
    impuesto DECIMAL(4,2) NOT NULL,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria)
);

-- Crear tabla de Clientes
CREATE TABLE Cliente (
    cedula VARCHAR(20) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- Crear tabla de Proveedores
CREATE TABLE Proveedor (
    id_proveedor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    ciudad VARCHAR(50)
);

-- Relación entre Proveedor y Producto
CREATE TABLE Producto_Proveedor (
    id_producto INT,
    id_proveedor INT,
    precio_compra DECIMAL(10,2),
    PRIMARY KEY (id_producto, id_proveedor),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto),
    FOREIGN KEY (id_proveedor) REFERENCES Proveedor(id_proveedor)
);

-- Tabla de Compras de Clientes
CREATE TABLE Compra (
    id_compra INT PRIMARY KEY AUTO_INCREMENT,
    cedula_cliente VARCHAR(20),
    fecha DATE,
    FOREIGN KEY (cedula_cliente) REFERENCES Cliente(cedula)
);

-- Detalle de cada compra
CREATE TABLE Detalle_Compra (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_compra INT,
    id_producto INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    impuesto DECIMAL(4,2),
    FOREIGN KEY (id_compra) REFERENCES Compra(id_compra),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
);

-- Tabla de pagos a proveedores
CREATE TABLE Pago_Proveedor (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_proveedor INT,
    fecha DATE,
    monto DECIMAL(10,2),
    FOREIGN KEY (id_proveedor) REFERENCES Proveedor(id_proveedor)
);