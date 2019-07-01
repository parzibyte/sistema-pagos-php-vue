CREATE TABLE IF NOT EXISTS sesiones(
id VARCHAR(255) NOT NULL PRIMARY KEY,
datos TEXT NOT NULL,
ultimo_acceso BIGINT UNSIGNED NOT NULL
);
create table usuarios(
    id bigint unsigned not null auto_increment,
    correo varchar(255) not null,
    palabra_secreta varchar(255) not null,
    primary key(id)
);

create table comun(
    clave varchar(255) not null primary key,
    valor varchar(255) not null
);
insert into usuarios
(correo, palabra_secreta) 
values 
('admin@gmail.com', '$2y$10$4rk0X6chjGucMGSkEsUZpeIQAFWOsXOrkJWS2v/0ZjQReD/iAF/V6');

create table personas(
    id bigint unsigned auto_increment,
    nombre varchar(255) not null,
    primary key(id)
);

create table pagos(
    id bigint unsigned auto_increment,
    id_persona bigint unsigned,
    monto decimal(9, 2) not null,
    fecha date not null,
    hash varchar(10) not null unique,
    primary key(id),
    foreign key(id_persona) references personas(id)
);