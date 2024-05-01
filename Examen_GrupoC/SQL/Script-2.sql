create database hospital;
use hospital;

create table empleado(
	id int not null auto_increment primary key,
	nombre varchar(100) not null,
	apellido varchar(100) not null
);

create table enfermedad(
	id int not null auto_increment primary key,
	nombre varchar(255) not null
);

create table municipio(
	id int not null auto_increment primary key,
	nombre varchar(255) not null
);

create table paciente (
	id int not null auto_increment primary key,
	nombre varchar(100) not null,
	apellido varchar(100) not null,
	direccion varchar(255) not null,
	sexo varchar(15) default 'No definido',
	tipo_sangre varchar(100) not null,
	id_municipio int not null,
	foreign key (id_municipio) references municipio(id)
);

create table hospitalizacion (
	id int not null auto_increment primary key,
	motivo_consulta varchar(100) not null,
	fecha_ingreso DATE not null,
	fecha_alta DATE not null,
	id_paciente int not null,
	id_empleado int not null,
	id_enfermedad int not null,
	foreign key (id_paciente) references paciente(id),
	foreign key (id_empleado) references empleado(id),
	foreign key (id_enfermedad) references enfermedad(id)
);

select * from empleado;
select * from paciente;
delete from empleado;

select * from empleado em 
	inner join hospitalizacion h on em.id = h.id_empleado
	inner join paciente p on h.id_paciente = p.id where em.id = 7;
	
select e.id, e.nombre from enfermedad e 
	inner join hospitalizacion h on e.id = h.id_enfermedad 
	inner join paciente p on p.id = h.id_paciente where p.id = 4;
	
select p.* from paciente p
	inner join municipio m on p.id_municipio = m.id where m.id = 2;