drop database weather;
create database weather default character set utf8;

use weather;

create table forecast
(
    countryId int  auto_increment primary key,
    countries varchar(15)
);
create table liveweather
(
    countries varchar(15),
    Wx varchar(30),
    MaxT int,
    MinT int,
    CI varchar(20),
    PoP int
);

insert into forecast (countries) values ('請選擇縣市');