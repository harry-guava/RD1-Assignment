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

insert into forecast (countries) values ('請選擇縣市'),('嘉義縣'),('新北市'),('嘉義市'),('新竹縣'),('新竹市'),('臺北市'),('臺南市'),('宜蘭縣'),('苗栗縣'),('雲林縣'),('花蓮縣'),('臺中市'),('臺東縣'),('桃園市'),('南投縣'),('高雄市'),('金門縣'),('屏東縣'),('基隆市'),('澎湖縣'),('彰化縣'),('連江縣');