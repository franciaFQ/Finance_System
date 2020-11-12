create database b22_25963593_sistemacontable;

use b22_25963593_sistemacontable;

create table rentas(
	codRenta int(6) not null auto_increment primary key,
	nombreRenta varchar(60) not null,
	desde float(7,2) not null,
	hasta float(7,2),
	porcentaje float(7,2),
	excesoDe float(7,2),
	cuotaFija float(7,2)
)engine=innoDB;

create table gruposcuentas(
	codGrupo int(10) not null auto_increment primary key,
	nombreGrupo varchar(60) not null
)engine=innoDB;

create table roles(
	codRol int(6) not null auto_increment primary key,
	nombreRol varchar(60) not null
)engine=innoDB;

create table tipoTrans(
	codTipo int(6) not null auto_increment primary key,
	nombreTrans varchar(60) not null
)engine=innoDB;

create table estados(
	codEstado int(6) not null auto_increment primary key,
	nombreEstado varchar(60) not null
)engine=innoDB;

create table cuentas(
	codCuenta int(10) not null primary key,
	nombreCuenta varchar(150) not null,
	codGrupo int(10),
	CONSTRAINT FK_cuentaGrupo FOREIGN KEY (codGrupo) REFERENCES gruposcuentas(codGrupo)
)engine=innoDB;

create table productos(
	codProd int(10) not null auto_increment primary key,
	nombreProd varchar(60) not null,
	cif float(7,2) not null
)engine=innoDB;

create table valoresBase(
	codValor int(6) not null auto_increment primary key,
	nombreValor varchar(60) not null,
	valor float(7,5) not null
)engine=innoDB;

create table usuarios(
	codUsuario int(6) not null auto_increment primary key,
	usuario varchar(20) not null,
	contrasena varchar(25) not null,
	codRol int(6) not null,
	CONSTRAINT FK_usuarioRol FOREIGN KEY (codRol) REFERENCES roles(codRol)
)engine=innoDB;

create table empleados(
	codEmpleado int(6) not null auto_increment primary key,
	nombreEmpleado varchar(80) not null,
	salarioBase float(7,2) not null,
	salarioxHora float(7,5) not null,
	horasextra float(7,2),
	horasnocturnas float(7,2),
	patrono boolean default 0,
	fechaingreso varchar(10) not null
)engine=innoDB;

create table materiasPrima(
	codMateria int(6) not null auto_increment primary key,
	nombreMateria varchar(60) not null,
	unidadMedida varchar(50) not null
)engine=innoDB;

create table detalleOrden(
	codDetalle int(6) not null auto_increment primary key,
	cantidadDetalle float(7,2) not null,
	codMateria int(6) not null,
	CONSTRAINT FK_detalleMateria FOREIGN KEY (codMateria) REFERENCES materiasPrima(codMateria)
)engine=innoDB;

create table ordenProduccion(
	codOrden int(6) not null auto_increment primary key,
	codDetalle int(6),
	cantidadHoras float(7,2) not null,
	codEstado int(6) not null,
	codProd int(6),
	CONSTRAINT FK_ordenDetalle FOREIGN KEY (codDetalle) REFERENCES detalleOrden(codDetalle),
	CONSTRAINT FK_detalleEstado FOREIGN KEY (codEstado) REFERENCES estados(codEstado),
	CONSTRAINT FK_ordenProducto FOREIGN KEY (codProd) REFERENCES productos(codProd)
)engine=innoDB;

create table transferencias(
	codTrans varchar(10) not null primary key,
	montoTrans float(7,2) not null,
	detalles varchar(60),
	fechatrans date not null
)engine=innoDB;

create table detallesTransferencias(
	codDetalleTrans int(6) not null auto_increment primary key,
	codTrans varchar(10) not null,
	codCuenta int(6) not null,
	codTipo int(6) not null,
	montoU float(7,2) not null,
	CONSTRAINT FK_transferenciasTipo FOREIGN KEY (codTipo) REFERENCES tipoTrans(codTipo),
	CONSTRAINT FK_detalletransferencia FOREIGN KEY (codTrans) REFERENCES transferencias(codTrans),
	CONSTRAINT FK_detallecuenta FOREIGN KEY (codCuenta) REFERENCES cuentas(codCuenta)
)engine=innoDB;

create table inventario(
	codInventario int(6) not null auto_increment primary key,
	cantidadInven float(7,2) not null,
	costoUnitario float(7,2) not null,
	total float(7,2) not null,
	fecha date not null,
	codTrans varchar(10) not null,
	codProd int(6),
	codTipo int(6) not null,
	CONSTRAINT FK_inventarioTipo FOREIGN KEY (codTipo) REFERENCES tipoTrans(codTipo),
	CONSTRAINT FK_inventariotransferencia FOREIGN KEY (codTrans) REFERENCES transferencias(codTrans),
	CONSTRAINT FK_inventarioproducto FOREIGN KEY (codProd) REFERENCES productos(codProd)
)engine=innoDB;

create table ventas(
	codVenta int(6) not null auto_increment primary key,
	cantidadInven float(7,2) not null,
	precioUnitario float(7,2) not null,
	cantidadVenta float(7,2) not null,
	total float(7,2) not null,
	iva float(7,2),
	codTrans varchar(10) not null,
	CONSTRAINT FK_ventatransferencia FOREIGN KEY (codTrans) REFERENCES transferencias(codTrans)
)engine=innoDB;

/*INSERT*/
-- ROLES
select * from roles u;-- de base 3

insert into roles values
(1, "contador"),(2, "planillero"),(3, "costos");

-- USUARIOS
select * from usuarios u;-- de base 3

insert into usuarios values
(1, "empcontador", "12345", 1),(2, "empplanilla", "12345", 2),(3, "empcostos", "12345", 3);

-- GRUPOS CUENTAS
select * from gruposcuentas g;-- de base 3

insert into gruposcuentas values
(1, "Activos Corrientes"),(2, "Activos Fijos"),(3, "Activos Diferidos"),(4,"Pasivos corrientes"),(5,"Pasivos no corrientes"),(6,"Capital");

-- CUENTAS
select * from cuentas c;-- de base 116
INSERT INTO cuentas (codCuenta, nombreCuenta, codGrupo) VALUES(1, 'ACTIVOS', NULL),(11, 'ACTIVOS CORRIENTES', NULL),(111, 'EFECTIVO Y EQUIVALENTES', NULL),(1111, 'CAJA GENERAL', 1),(1112, 'CAJA CHICA', 1),(1113, 'BANCO AGRICOLA', 1),(1114, 'BANCO CUSCATLAN', 1),(1115, 'BANCO DAVIVIENDA', 1),(1116, 'BANCO HIPOTECARIO', 1),(1117, 'BANCO DE FOMENTO AGROPECUARIO', 1),(1118, 'BANCO AZTECA', 1),(112, 'CUENTAS POR COBRAR', NULL)
,(1121, 'CUENTAS POR COBRAR CLIENTES', 1),(1122, 'CUENTAS POR COBRAR FUNCIONARIO', 1),(1123, 'CUENTAS POR COBRAR EMPLEADO', 1),(1124, 'PROVISIONES DE CUENTAS INCOBRABLES', 1),(113, 'INVENTARIOS', NULL),(1131, 'INVENTARIOS DE MATERIA PRIMA', NULL),(11311, 'INVENTARIO DE MAIZ', 1),(11312, 'INVENTARIO DE MELAZA DE CAÑA', 1),(11313, 'INVENTARIO DE TRIGO', 1),(11314, 'INVENTARIO DE CASCARILLA DE MANÍ', 1),(11315, 'INVENTARIO DE CARBONATO DE CALCIO', 1),(11316, 'INVENTARIO DE UREA', 1),(11317, 'INVENTARIO DE SAL COMUN', 1),
(11318, 'INVENTARIO DE MEZCLA COMERCIAL DE VITAMINAS', 1),(11319, 'INVENTARIO DE SOYA', 1),(113110, 'INVENTARIO DE FOSFATO DICALCICO', 1),(113111, 'INVENTARIO DE GLICERINA', 1),(113112, 'INVENTARIO DE SACOS', 1),(1132, 'INVENTARIOS DE PRODUCTOS EN PROCESO', NULL),(11321, 'INVENTARIO DE PRODUCTO EN PROCESO DE CONCENTRADO MANTENIMIENTO 12%', 1),(11322, 'INVENTARIO DE PRODUCTO EN PROCESO DE CONCENTRADO LEHCERO 12%', 1),(11323, 'INVENTARIO DE PRODUCTO EN PROCESO DE CONCENTRADO LECHERO CON GLICERINA 23%', 1),(1133, 'INVENTARIOS DE PRODUCTOS TERMINADOS', NULL),
(11331, 'INVENTARIO DE CONCENTRADO MANTENIMIENTO 12%', 1),(11332, 'INVENTARIO DE CONCENTRADO LECHERO 23%', 1),(11333, 'INVENTARIO DE CONCENTRADO LECHERO CON GLICERINA 23%', 1),(114, 'IVA CREDITO FISCAL', NULL),(115, 'IVA CONSOLIDADO: IVA POR COBRAR', 1),(116, 'LOCAL', 1),(117, 'SEGUROS', 1),(118, 'DOCUMENTOS POR COBRAR', 1),(119, 'SUMINISTROS DE OFICINA', 1),(12, 'INSTRUMENTOS FINANCIEROS (INVERSIONES)', NULL),(121, 'ACCIONES EN OTRA COMPAÑÍA', 1),(122, 'BONOS', 1),(123, 'INTERESES POR COBRAR', 1),(13, 'ACTIVOS FIJOS', NULL),(131, 'PROPIEDAD, PLANTA Y EQUIPO', NULL),(1311, 'TERRENOS', 2),(1312, 'EDIFICIOS', 2),(1313, 'EQUIPO DE OFICINA', 2),
(1314, 'PATENTES', 2),(1315, 'MAQUINARIA', 2),(1316, 'VEHICULO', 2),(1317, 'MOBILIARIO', 2),(1318, 'DEPRECICIACION ACUMULADA', 2),(1319, 'AMORTIZACION ACUMULADA', 2),(14, 'ACTIVOS DIFERIDOS', 3),(141, 'ACTIVOS INTANGIBLES', 3),(2, 'PASIVOS', NULL),(21, 'PASIVOS CORRIENTES', NULL),(211, 'CUENTAS POR PAGAR', 4),(212, 'SUELDOS POR PAGAR', 4),(213, 'RETENCIONES Y CUOTAS POR PAGAR', NULL),(2131, 'ISSS POR PAGAR', 4),(2132, 'AFP POR PAGAR', 4),(2133, 'ISR POR PAGAR', 4),(214, 'INTERESES POR PAGAR', 4),(215, 'PRESTAMOS A CORTO PLAZO', 4),(216, 'PROVEEDORES', 4),(217, 'IVA DEBITO FISCAL', NULL),(218, 'IVA CONSOLIDADO: IVA POR PAGAR', 4),(219, 'INGRESO POR PRODUCTO NO ENTREGADO', 4),
(2110, 'DOCUMENTOS POR PAGAR', 5),(2111, 'PROVISIONES A LARGO PLAZO', 5),(3, 'CAPITAL', NULL),(31, 'CAPITAL PROPIO', 6),(32, 'UTILIDADES', NULL),(33, 'PERDIDAS', NULL),(34, 'UTILIDADES RETENIDAS', NULL),(4, 'INGRESOS', NULL),(41, 'INGRESOS POR VENTAS', 6),(42, 'INGRESO POR DEVOLUCIONES', 6),(43, 'INGRESO POR DESCUENTO SOBRE COMPRA', 6),(44, 'INTERESES GANADOS', 6),(45, 'OTROS INGRESOS', 6),(5, 'COSTO DE LA MERCANCIA VENDIDA', NULL),(51, 'COSTO DE VENTA', 6),(52, 'FLETES EN COMPRAS', 6),(6, 'GASTOS', NULL),(61, 'GASTOS DE VENTAS', NULL),(611, 'GASTOS POR COMISIONES SOBRE VENTAS', 6),(612, 'GASTOS POR DESCUENTOS SOBRE VENTAS', 6),(613, 'GASTOS DE MERCADOTECNIA', 6),(614, 'GASTOS DE PUBLICIDAD', 6),
(615, 'GASTOS DE TRANSPORTE', 6),(62, 'GASTOS GENERALES Y ADMINISTRATIVOS', NULL),(621, 'GASTOS POR SALARIOS', 6),(622, 'ISSS', 6),(623, 'AFP', 6),(624, 'ISR', 6),(625, 'INSAFORD', 6),(626, 'BONIFICACIONES', 6),(627, 'VACACIONES', 6),(628, 'GASTOS POR DEPRECIACION', 6),(629, 'GASTOS POR SEGURO', 6),(6210, 'GASTOS POR ALQUILER', 6),(6211, 'GASTOS DE INSTALACION', 6),(6212, 'GASTOS DE REPARACION', 6),(6213, 'GASTOS DE SERVICIOS BASICOS', 6),(6214, 'GASTOS POR AMORTIZACION', 6),(6215, 'GASTOS DE COMBUSTIBLE', 6),(6216, 'GASTOS POR INTERESES SOBRE PRESTAMOS', 6),(6217, 'GASTOS POR AGUINALDO', 6),(6218, 'GASTOS PERSONALES', 6),(6219, 'OTROS GASTOS', 6);

-- VALORES BASE
select * from valoresbase v;-- de base 7

insert into valoresbase (codValor, nombreValor, valor) values
(1, "ISSS EMPLEADO", 0.03), (2, "ISSS PATRONAL", 0.075), (3, "AFP", 0.0725), (4, "AFP PATRONAL", 0.0775),
(5, "HORAS NOCTURNAS", 0.25), (6, "VACACIONES", 0.3), (7, "INSAFORP", 0.01), (8, "HORAS EXTRAS", 2), (9, "CIF",6.9615 ),(10, "COSTOXHORA", 2.04);

-- PRODUCTOS
select * from productos p;-- de base 3

insert into productos (codProd, nombreProd, cif) values
(1, "CONCENTRADO DE MANTENIMIENTO 12%", 0.0), (2, "CONCENTRADO LECHERO 23%", 0.0), (3, "CONCENTRADO LECHERO CON GLICERINA 23%", 0.0);

-- MATERIAS PRIMAS
select * from materiasprima m;-- de base 12

insert into materiasprima (codMateria, nombreMateria, unidadMedida ) values
(1, "INVENTARIO DE MAIZ", "QUINTALES"),(2, "INVENTARIO DE TRIGO", "QUINTALES"), (3, "INVENTARIO DE SOYA", "QUINTALES"), (4,"INVENTARIO DE MELAZA DE CAÑA", "BARRILES"),
(5, "INVENTARIO DE CASCARILLA DE MANÍ", "QUINTALES"), (6, "INVENTARIO DE SAL COMÚN", "QUINTALES"),(7, "INVENTARIO DE UREA", "QUINTALES"),(8, "INVENTARIO DE CARBONATO DE CALCIO", "QUINTALES"),
(9, "INVENTARIO DE FOSFATO DICALCICO", "QUINTALES"), (10, "INVENTARIO DE MEZCLA COMERCIAL DE VITAMINAS", "QUINTALES"), (11, "INVENTARIO DE GLICERINA", "QUINTALES"), (12, "INVENTARIO DE SACOS", "SACOS");

-- TIPO TRANSFERENCIAS
select * from tipotrans t;-- de base 4

insert into tipotrans (codTipo, nombreTrans ) values
(1, "DEBE"), (2,"HABER"),(3, "VENTA"), (4, "COMPRA");

-- ESTADOS
select * from estados e;-- de base 3

insert into estados (codEstado, nombreEstado) values
(1, "COMPLETADO"), (2, "VENDIDO"), (3, "PENDIENTE");

-- EMPLEADOS
select * from empleados;-- de base 3

insert into empleados (codEmpleado, nombreEmpleado, salarioBase, salarioxHora, horasextra, horasnocturnas, patrono, fechaingreso) values
(1, "Rodrigo Francia", 500.00, 2.0833, 0,0,1, "11-06-2019"),(2, "Manuel Argueta", 500.00, 2.0833, 0,5,0, "11-06-2017"),
(3, "Henry Zepeda", 1000.00, 4.1667, 4,0,0,"11-06-2000");

-- RENTA
select * from rentas;

insert into rentas (codRenta, nombreRenta, desde, hasta, porcentaje, excesoDe, cuotaFija) values
(1, "1 TRAMO", 0.01, 236, NULL, NULL, NULL), (2, "2 TRAMO", 236.01, 447.62, 0.1, 236, 8.83),
(3, "3 TRAMO", 447.63, 1019.05, 0.2,447.62, 30.00), (4, "4 TRAMO", 1019.06, null, 0.3, 1019.05, 144.28);

-- ajuste a valor, agregado de horas extra, isss mayor, creaccion de tabla renta, agregar fecha de contratacion empleados
select * from empleados;
select * from valoresbase v;
select * from tipotrans t2;
select * from transferencias t;
select * from detallestransferencias d;
select * from inventario;
select t.codTrans, dt.montoU, t.detalles, tt.nombreTrans, c.nombreCuenta from transferencias t
inner join detallestransferencias dt on dt.codTrans = t.codTrans
inner join tipotrans tt on tt.codTipo = dt.codTipo
inner join cuentas c on c.codCuenta = dt.codCuenta limit 10;


select c.nombreCuenta, tt.nombreTrans, sum(dt.montoU) monto, t.fechatrans from cuentas c
inner join detallestransferencias dt on dt.codCuenta = c.codCuenta
inner join tipotrans tt on tt.codTipo = dt.codTipo
inner join transferencias t on t.codTrans = dt.codTrans
where t.fechatrans like '2020-06%'
group by c.nombreCuenta, tt.nombreTrans;

select concat('INVENTARIO DE ', m2.nombreMateria) from materiasprima m2 ;
select * from cuentas;
SELECT * FROM tipotrans where codTipo = 1 or codTipo = 2;

select t.codTrans, dt.montoU, t.detalles, tt.nombreTrans, c.nombreCuenta, t.fechatrans from transferencias t
inner join detallestransferencias dt on dt.codTrans = t.codTrans
inner join tipotrans tt on tt.codTipo = dt.codTipo
inner join cuentas c on c.codCuenta = dt.codCuenta limit 10;
                         
select * from cuentas c where c.nombreCuenta in (
	select concat('INVENTARIO DE ', m2.nombreMateria) from materiasprima m2 
); 

select * from gruposcuentas;
select * from cuentas;
select * from inventario i2;
SELECT * FROM detallestransferencias;
select * from tipotrans t2;
/*venta=haber
compra=debe*/
select * from materiasprima m2 where nombreMateria = 'INVENTARIO DE SOYA';
select * from transferencias t;
select * from inventario i2;
select * from materiasprima m;
-- 41 INGRESO POR VENTAS
-- 51 COSTO DE VENTA
-- 61 or 6219 - 624
-- 6216 
-- 45 OTROS INGRESOS AND 42
select d.codCuenta, c.nombreCuenta, tt.nombreTrans,
case
when ((select sum(d2.montoU) monto from detallestransferencias d2 where d2.codTipo in (1,4) and d2.codCuenta = d.codCuenta group by d2.codCuenta, d2.codTipo) - 
(select sum(d1.montoU) monto from detallestransferencias d1 where d1.codTipo in (2,3) and d1.codCuenta = d.codCuenta group by d1.codCuenta, d1.codTipo) is null)
then
	d.montoU
else 
	ABS((select sum(d2.montoU) monto from detallestransferencias d2 where d2.codTipo in (1,4) and d2.codCuenta = d.codCuenta group by d2.codCuenta, d2.codTipo) - 
(select sum(d1.montoU) monto from detallestransferencias d1 where d1.codTipo in (2,3) and d1.codCuenta = d.codCuenta group by d1.codCuenta, d1.codTipo))
end as monto 
from detallestransferencias d 
inner join cuentas c on c.codCuenta = d.codCuenta
inner join tipotrans tt on tt.codTipo = d.codTipo
inner join transferencias t on t.codTrans = d.codTrans
where c.codCuenta in (41, 51, 6216, 45, 42) or c.codCuenta like '61%' or c.codCuenta like '62%' and c.codCuenta != 624 and 
t.fechatrans like '2020-07-%' group by d.codCuenta;

delete from detallestransferencias where codTrans = 'PLA6';

select * from cuentas where codCuenta in (41, 51, 6216, 45, 42) or codCuenta like '61%' or codCuenta like '62%' and codCuenta != 624;
select * from empleados e;

select * from valoresbase v2;
select * from ordenproduccion;
select * from cuentas;
select * from productos p2;
select o.codOrden, p.nombreProd, o.cantprod, o.costototal,
o.canthoras, o.cifmonto, o.cstmobra, o.estado, o.fecha, o.cstunif from ordenproduccion o 
inner join productos p on p.codProd = o.codProd;


SELECT codTrans FROM transferencias where codTrans like 'PLA%' order by codTrans limit 1;


SELECT o.codOrden, o.costototal, o.cstunif, o.cantprod , p.nombreProd FROM ordenproduccion o
inner join productos p on p.codProd = o.codProd where estado = 'FINALIZADO';