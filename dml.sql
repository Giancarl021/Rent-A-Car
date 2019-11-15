/* DML */

create database db_rentacar;

create table Car (
	carPlate char(7) primary key, -- ABC0123 / BRA1S12
	carYear smallint not null, -- 2017
	model varchar(20) not null, -- Corsa Sedan
	description varchar(240) not null, -- 250l de porta-malas, verde, cromado, rádio, ar condicionado [...]
	km int not null, -- 10000
	kmPrice double not null, -- 15
	-- situation boolean not null, -- Alugado (true) / Disponível (false)
	dailyTax double not null, -- 20
	observations varchar(240) -- Clonado
);

create table Client (
	cpf char(11) primary key, -- 12345678900
	name varchar(50) not null, -- Jão Batista do Carvalho
	address varchar(150) not null, -- Rua dos Bobos, 0
	telephone char(11) not null, -- 51912345678
	debt double -- 500
);

create table Rent (
    id bigint unsigned auto_increment primary key,
	clientCpf char(11),
	carPlate char(7),
	-- expired boolean default false,
	initDate timestamp not null,
	expirationDate timestamp,
	foreign key(clientCpf) references Client(cpf),
	foreign key(carPlate) references Car(carPlate)
);

/* select * from c as Car
join r as Rent on c.carPlate = r.carPlate
where coalesce(r.expirationDate, r.initDate) <> r.initDate and c.carPlate = ${value} */

-- select sum(c.debt) from Client;