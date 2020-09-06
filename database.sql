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
    countryId int,
    Wx varchar(30),
    MaxT int,
    MinT int,
    CI varchar(20),
    PoP int
);
create table twoday
(
    countryId int,
    Wx varchar (15),
    WxV int,
    T int,
    CI varchar(20),
    PoP int,
    `Time` datetime,
    `date` date   
);
create table sevendays
(
   countryId int,
   fTime datetime,
   Wx varchar (15),
   WxV int,
   T varchar(20),
   CI varchar(20),
   Wind varchar(20),
   Hum varchar(20)
);
create table rainview
(
    stationId varchar(20),
    `site` varchar(20),
    city varchar(20),
    Rain float(6,2),
    Rain24 float(8,2)
);
   


insert into forecast (countries) values ('嘉義縣'),('新北市'),('嘉義市'),('新竹縣'),('新竹市'),('臺北市'),('臺南市'),('宜蘭縣'),('苗栗縣'),('雲林縣'),('花蓮縣'),('臺中市'),('臺東縣'),('桃園市'),('南投縣'),('高雄市'),('金門縣'),('屏東縣'),('基隆市'),('澎湖縣'),('彰化縣'),('連江縣');