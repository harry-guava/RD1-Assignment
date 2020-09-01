drop database weather;
create database weather default character set utf8;

use weather;

create table forecast
(
    countryId int  auto_increment primary key,
    countries varchar(15)
)