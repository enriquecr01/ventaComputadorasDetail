use master
GO
create database computadoras
GO
use computadoras
GO

-- Crear logins
CREATE LOGIN admin WITH PASSWORD = 'admin', DEFAULT_DATABASE = computadoras;
CREATE LOGIN vendedor WITH PASSWORD = 'abc123', DEFAULT_DATABASE = computadoras; 
GO

CREATE ROLE VendedorBoletos;
CREATE ROLE Administrador;

-- Crear usuarios
CREATE USER admin FOR LOGIN admin;
CREATE USER vendedor FOR LOGIN vendedor;

EXEC sp_addrolemember 'Administrador', 'admin'; 
EXEC sp_addrolemember 'VendedorBoletos', 'vendedor';

GO
/*
ALTER ROLE Administrador ADD MEMBER admin;
ALTER ROLE VendedorBoletos ADD MEMBER vendedor;
*/

CREATE SCHEMA ventas;
GO
CREATE SCHEMA rh;
GO
CREATE SCHEMA catalogo;
GO

CREATE TABLE catalogo.marcas
(
	id tinyint primary key identity(1,1),
	nombre varchar(20)
);

create table catalogo.modelos
(
	id smallint primary key identity(1,1),
	nombre varchar(30),
	descripcion varchar(200),
	marca tinyint references catalogo.marcas(id),
	precio decimal
);

create table ventas.ventas
(
	id int primary key identity(1,1),
	fecha datetime,
	status CHAR(2) NOT NULL DEFAULT 'UP',
	CONSTRAINT CHK_Empleados_status CHECK(status IN('UP', 'DW'))
);

CREATE TABLE catalogo.perfiles_ctg
(
	clave CHAR(2) primary key,
	nombre VARCHAR(20) NOT NULL
);

CREATE TABLE rh.empleados
(
	control SMALLINT PRIMARY KEY identity(100, 1),
	apPaterno VARCHAR(25) NOT NULL,
	apMaterno VARCHAR(25),
	nombre VARCHAR(25) NOT NULL,
	fechaContratacion date,
	status CHAR(2) NOT NULL DEFAULT 'UP',
	CONSTRAINT CHK_Empleados_status CHECK(status IN('UP', 'DW'))
);

CREATE TABLE rh.usuarios
(
	empleado SMALLINT PRIMARY KEY,
	perfil CHAR(2) NOT NULL,
	nombre VARCHAR(15) NOT NULL,
	contrasena VARCHAR(99) NOT NULL,
	status CHAR(2) NOT NULL DEFAULT 'UP',
	CONSTRAINT FK_Usuarios_PerfilesCtg FOREIGN KEY(perfil) REFERENCES catalogo.[perfiles_ctg](clave),
	CONSTRAINT FK_Usuarios_Empleados FOREIGN KEY(empleado) REFERENCES rh.[empleados](control),
	CONSTRAINT CHK_Usuarios_status CHECK(status IN('UP', 'DW'))
);


create table ventas.detalle
(
	id int primary key identity(1,1),
	producto smallint references catalogo.modelos(id),
	venta int references ventas.ventas(id),
	vendedor smallint references rh.empleados(control),
	precio decimal,
	cantidad tinyint
);

/*Permisos para administrador*/
GRANT SELECT ON OBJECT::catalogo.marcas TO Administrador
GRANT INSERT ON OBJECT::catalogo.marcas TO Administrador

GRANT SELECT ON OBJECT::catalogo.modelos TO Administrador
GRANT INSERT ON OBJECT::catalogo.modelos TO Administrador
GRANT UPDATE ON OBJECT::catalogo.modelos TO Administrador

GRANT SELECT ON OBJECT::rh.empleados TO Administrador
GRANT INSERT ON OBJECT::rh.empleados TO Administrador
GRANT UPDATE ON OBJECT::rh.empleados TO Administrador

GRANT SELECT ON OBJECT::rh.usuarios TO Administrador
GRANT INSERT ON OBJECT::rh.usuarios TO Administrador
GRANT UPDATE ON OBJECT::rh.usuarios TO Administrador

GRANT SELECT ON OBJECT::ventas.ventas TO Administrador
GRANT INSERT ON OBJECT::ventas.ventas TO Administrador
GRANT UPDATE ON OBJECT::ventas.ventas TO Administrador

GRANT SELECT ON OBJECT::ventas.detalle TO Administrador
GRANT INSERT ON OBJECT::ventas.detalle TO Administrador

/*Permisos para vendedor*/
GRANT SELECT ON OBJECT::catalogo.marcas TO Vendedor

GRANT SELECT ON OBJECT::catalogo.modelos TO Vendedor

GRANT SELECT ON OBJECT::rh.empleados TO Vendedor

GRANT SELECT ON OBJECT::rh.usuarios TO Vendedor

GRANT SELECT ON OBJECT::ventas.ventas TO Vendedor
GRANT INSERT ON OBJECT::ventas.ventas TO Vendedor

GRANT INSERT ON OBJECT::ventas.detalle TO Vendedor

INSERT INTO catalogo.perfiles_ctg(clave, nombre) values
('AD','Administrador'),
('VE','Vendedor');

GO
/*
declare @respuesta varchar(250)
exec usp_iUsuarios 'Juarez', 'Cendejas', 'Eric Luis', 'admin','VB', @respuesta output
select @respuesta
*/
select * from rh.usuarios
/*Registro de vendedor*/
INSERT INTO rh.[empleados](apPaterno, apMaterno, nombre, status, fechaContratacion) values
			('Juarez', 'Cendejas', 'Eric Luis', 'UP', GETDATE());
			select * from rh.empleados
insert into rh.[usuarios] (empleado, perfil ,nombre, contrasena, status) values
		(100, 'VE', lower('EJUAREZ'), hashbytes('sha1', '1234567'), 'UP')
		
/*Registro de administrador*/
INSERT INTO rh.[empleados](apPaterno, apMaterno, nombre, status, fechaContratacion) values
			('Chavez', 'Romero', 'Enrique', 'UP', GETDATE());
			select * from rh.empleados
insert into rh.[usuarios] (empleado, perfil ,nombre, contrasena, status) values
		(101, 'AD', lower('echavez'), hashbytes('sha1', '1234567'), 'UP')
		
select mo.id, mo.nombre, ma.nombre, mo.precio  from catalogo.modelos as mo
inner join catalogo.marcas as ma on mo.marca = ma.id

INSERT INTO catalogo.marcas(NOMBRE) VALUES
('Dell'),
('HP'),
('Toshiba'),
('Acer');

select mo.id as id, mo.nombre as nombre, ma.nombre as marca, mo.precio as precio, mo.descripcion as descr from catalogo.modelos as mo
      inner join catalogo.marcas as ma on mo.marca = ma.id WHERE mo.id = 7

INSERT INTO catalogo.modelos(nombre, descripcion, precio, marca) VALUES
('Vostro 3250', 'Memoria RAM de 4GB, Disco Duro SATA 500GB, Procesador INTEL CORE I5', 11500, 1),
('Optiplex 3050 Ci5', 'Memoria RAM de 8GB, Disco Duro SATA 1TB, Procesador INTEL CORE I5', 18984, 1),
('Inspiron 3268', 'Memoria RAM de 4GB, Disco Duro SATA 1TB, Procesador INTEL CORE I3', 10609, 1),
('Optiplex ', 'Memoria RAM de 8GB, Disco Duro SATA 1TB, Procesador INTEL CORE I7', 27740, 1)


INSERT INTO ventas.ventas(fecha) VALUES(GETDATE());

SELECT top 1 id from ventas.ventas order by id desc 
select * from ventas.detalle

INSERT INTO ventas.detalle(producto, venta, vendedor, precio, cantidad) VALUES(1, 1, 100, 11500, 1);
INSERT INTO ventas.detalle(producto, venta, vendedor, precio, cantidad) VALUES(1, 1, 100, 18984, 2);

/*Consultar el detalle de la venta*/
SELECT de.id, mo.nombre, ma.nombre, em.nombre, v.fecha, de.cantidad, de.precio, (de.cantidad * de.precio) as costoTotal
FROM ventas.detalle as de
inner join catalogo.modelos as mo on de.producto = mo.id
inner join catalogo.marcas as ma on ma.id = mo.marca
inner join rh.empleados as em on em.control = de.vendedor
inner join ventas.ventas as v  on v.id = de.venta
where de.venta = 7

SELECT de.venta, v.fecha,SUM((de.cantidad * de.precio)) as costoTotal
FROM ventas.detalle as de
inner join ventas.ventas as v  on v.id = de.venta
inner join rh.empleados as e on e.control = de.vendedor
where v.status = 'UP'
GROUP BY de.venta, v.fecha

select ID, fecha, status 
from ventas.ventas
where id = 1

SELECT de.id as id, mo.id as idProd, mo.descripcion as descripcion, mo.nombre as producto, ma.nombre as marca, em.nombre as empleado, v.id as venta, de.cantidad as qty, de.precio as precio, (de.cantidad * de.precio) as costoTotal
				FROM ventas.detalle as de
				inner join catalogo.modelos as mo on de.producto = mo.id
				inner join catalogo.marcas as ma on ma.id = mo.marca
				inner join rh.empleados as em on em.control = de.vendedor
				inner join ventas.ventas as v  on v.id = de.venta
				where de.venta = 1
				
				select control, apPaterno, apMaterno, nombre, fechaContratacion
					from rh.empleados
					where control = 100
					
UPDATE ventas.ventas SET status = 'DW' where id = 7