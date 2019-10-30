/* DML */

create database db_rentacar;

create table Car (
	carPlate char(7) primary key, -- ABC0123 / BRA1S12
	carYear smallint not null, -- 2017
	model varchar(20) not null, -- Corsa Sedan
	description varchar(240) not null, -- 250l de porta-malas, verde, cormado, rádio, ar condicionado [...]
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
	telephone char(14) not null, -- +5551912345678
	debt double -- 500
);

create table Rent (
	clientCpf char(11),
	carPlate char(7),
	-- expired boolean default false,
	initDate timestamp not null,
	expirationDate timestamp,
	constraint pkRent primary key(clientCpf, carPlate, initDate),
	foreign key(clientCpf) references Client(cpf),
	foreign key(carPlate) references Car(carPlate)
);

/* select * from r as Rent
join c as Car on car.carPlate = Rent.carPlate
where coalesce(r.expirationDate, r.initDate) <> r.initDate and c.carPlate = ${value} */
