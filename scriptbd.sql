-- Crear las tablas

-- Tabla Almacen
CREATE TABLE Almacen (
    ID_Almacen SERIAL PRIMARY KEY,
    Nombre VARCHAR(255),
    Descripcion TEXT,
    Cantidad INT
);

-- Tabla Empleado
CREATE TABLE Empleado (
    ID_Empleado SERIAL PRIMARY KEY,
    ID_Almacen INT,
    Rol VARCHAR(255),
    Nombres VARCHAR(255),
    Apellidos VARCHAR(255),
    Correo VARCHAR(255),
    Telefono VARCHAR(20),
    Genero VARCHAR(10),
    CONSTRAINT fk_almacen FOREIGN KEY (ID_Almacen) REFERENCES Almacen(ID_Almacen)
);

-- Tabla Producto
CREATE TABLE Producto (
    ID_Producto SERIAL PRIMARY KEY,
    ID_Almacen INT,
    Grupo VARCHAR(255),
    Cantidad INT,
    Descripcion TEXT,
    Unidad VARCHAR(50),
    Fecha_Vencimiento DATE,
    Fecha_Ingreso DATE,
    CONSTRAINT fk_almacen_producto FOREIGN KEY (ID_Almacen) REFERENCES Almacen(ID_Almacen)
);

-- Tabla Proveedor
CREATE TABLE Proveedor (
    RUC VARCHAR(20) PRIMARY KEY,
    Razon_Social VARCHAR(255)
);

-- Tabla Documento_Ingreso
CREATE TABLE Documento_Ingreso (
    ID_Documento_Ingreso SERIAL PRIMARY KEY,
    ID_Empleado INT,
    RUC VARCHAR(20),
    Fecha_Ingreso DATE,
    Subtotal NUMERIC(10,2),
    IGV NUMERIC(10,2),
    Tipo_Documento VARCHAR(50),
    CONSTRAINT fk_empleado FOREIGN KEY (ID_Empleado) REFERENCES Empleado(ID_Empleado),
    CONSTRAINT fk_proveedor FOREIGN KEY (RUC) REFERENCES Proveedor(RUC)
);

-- Tabla Detalle_Documento_Ingreso
CREATE TABLE Detalle_Documento_Ingreso (
    ID_Detalle_Documento_Ingreso SERIAL PRIMARY KEY,
    ID_Producto INT,
    ID_Documento_Ingreso INT,
    Cantidad INT,
    Precio_Unitario NUMERIC(10,2),
    Precio_Total NUMERIC(10,2),
    Observaciones TEXT,
    CONSTRAINT fk_producto FOREIGN KEY (ID_Producto) REFERENCES Producto(ID_Producto),
    CONSTRAINT fk_documento_ingreso FOREIGN KEY (ID_Documento_Ingreso) REFERENCES Documento_Ingreso(ID_Documento_Ingreso)
);

-- Funciones para cálculos

-- Calcular el precio total de un detalle de documento de ingreso
CREATE OR REPLACE FUNCTION calcular_precio_total(cantidad INT, precio_unitario NUMERIC)
RETURNS NUMERIC AS $$
BEGIN
    RETURN cantidad * precio_unitario;
END;
$$ LANGUAGE plpgsql;

-- Calcular el subtotal de un documento de ingreso (sin IGV)
CREATE OR REPLACE FUNCTION calcular_subtotal(id_documento_ingreso INT)
RETURNS NUMERIC AS $$
DECLARE
    subtotal NUMERIC := 0;
BEGIN
    FOR rec IN
        SELECT d.Cantidad, d.Precio_Unitario
        FROM Detalle_Documento_Ingreso d
        WHERE d.ID_Documento_Ingreso = id_documento_ingreso
    LOOP
        subtotal := subtotal + calcular_precio_total(rec.Cantidad, rec.Precio_Unitario);
    END LOOP;
    RETURN subtotal;
END;
$$ LANGUAGE plpgsql;

-- Calcular IGV
CREATE OR REPLACE FUNCTION calcular_igv(subtotal NUMERIC)
RETURNS NUMERIC AS $$
BEGIN
    RETURN subtotal * 0.18; -- 18% de IGV
END;
$$ LANGUAGE plpgsql;

-- Procedimiento para insertar un nuevo documento de ingreso
CREATE OR REPLACE PROCEDURE insertar_documento_ingreso(
    p_id_empleado INT,
    p_ruc VARCHAR,
    p_fecha_ingreso DATE,
    p_tipo_documento VARCHAR
)
LANGUAGE plpgsql
AS $$
DECLARE
    v_subtotal NUMERIC;
    v_igv NUMERIC;
    v_total NUMERIC;
BEGIN
    -- Insertar documento de ingreso
    INSERT INTO Documento_Ingreso (ID_Empleado, RUC, Fecha_Ingreso, Tipo_Documento)
    VALUES (p_id_empleado, p_ruc, p_fecha_ingreso, p_tipo_documento)
    RETURNING ID_Documento_Ingreso INTO v_id_documento_ingreso;

    -- Calcular el subtotal
    v_subtotal := calcular_subtotal(v_id_documento_ingreso);
    -- Calcular IGV
    v_igv := calcular_igv(v_subtotal);
    -- Calcular el total
    v_total := v_subtotal + v_igv;

    -- Actualizar el documento con los cálculos
    UPDATE Documento_Ingreso
    SET Subtotal = v_subtotal, IGV = v_igv
    WHERE ID_Documento_Ingreso = v_id_documento_ingreso;
END;
$$;

-- Procedimiento para insertar un detalle de documento de ingreso
CREATE OR REPLACE PROCEDURE insertar_detalle_documento_ingreso(
    p_id_producto INT,
    p_id_documento_ingreso INT,
    p_cantidad INT,
    p_precio_unitario NUMERIC,
    p_observaciones TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO Detalle_Documento_Ingreso (ID_Producto, ID_Documento_Ingreso, Cantidad, Precio_Unitario, Precio_Total, Observaciones)
    VALUES (p_id_producto, p_id_documento_ingreso, p_cantidad, p_precio_unitario, calcular_precio_total(p_cantidad, p_precio_unitario), p_observaciones);
END;
$$;

-- Procedimiento para insertar un producto
CREATE OR REPLACE PROCEDURE insertar_producto(
    p_id_almacen INT,
    p_grupo VARCHAR,
    p_cantidad INT,
    p_descripcion TEXT,
    p_unidad VARCHAR,
    p_fecha_vencimiento DATE,
    p_fecha_ingreso DATE
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO Producto (ID_Almacen, Grupo, Cantidad, Descripcion, Unidad, Fecha_Vencimiento, Fecha_Ingreso)
    VALUES (p_id_almacen, p_grupo, p_cantidad, p_descripcion, p_unidad, p_fecha_vencimiento, p_fecha_ingreso);
END;
$$;

