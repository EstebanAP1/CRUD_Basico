SELECT p.proc FROM procesadores p
INNER JOIN equipos e ON p.id = e.id_proc;

INSERT INTO procesadores(proc) VALUES('Intel Core i7');
INSERT INTO ram(ram) VALUES('16 GB');
INSERT INTO discos(disco) VALUES('1 TB');


INSERT INTO departamentos(id_depa, depa) VALUES ("02", "Cesar");
SELECT * FROM departamentos;

INSERT INTO municipios(id_muni, muni) VALUES ("01005", "Santa Veronica");
SELECT * FROM municipios;

SELECT * FROM equipos.procesadores p
INNER JOIN equipos.discos d
INNER JOIN equipos.ram r;

SELECT * FROM usuarios u
INNER JOIN municipios m ON u.id_muni = m.id_muni
INNER JOIN departamentos d ON LEFT(m.id_muni, 2) = d.id_depa;


SELECT * FROM municipios m
INNER JOIN departamentos d ON LEFT(m.id_muni, 2) = d.id_depa
WHERE id_depa = "01";

INSERT INTO usuarios(cedula, nombre, apellido, id_depa, id_muni)
VALUES(1, "DISPONIBLE", "DISPONIBLE", "01", "01001");

SELECT * FROM usuarios;

SELECT d.depa FROM usuarios u
INNER JOIN departamentos d ON u.id_depa = d.id_depa
WHERE u.id_depa = "01";

SELECT d.depa, CONCAT(u.nombre, " ", u.apellido) AS nombre FROM equipos e
INNER JOIN usuarios u ON u.id_usuario = e.id_usuario
INNER JOIN departamentos d ON d.id_depa = u.id_depa
WHERE e.id_usuario = "1";


SELECT * FROM equipos;

SELECT e.id_equipo, e.id_usuario, e.serial, e.monitor, e.teclado, e.mouse, p.id_proc, p.proc, d.id_disco, d.disco, r.id_ram, r.ram, dep.depa, CONCAT(u.nombre, ' ', u.apellido) AS nombre
FROM equipos e
INNER JOIN usuarios u ON u.id_usuario = e.id_usuario
INNER JOIN departamentos dep ON dep.id_depa = u.id_depa
INNER JOIN procesadores p ON p.id_proc = e.id_proc
INNER JOIN discos d ON d.id_disco = e.id_disco
INNER JOIN ram r ON r.id_ram = e.id_ram;