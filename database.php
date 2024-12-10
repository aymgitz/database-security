<?php 
  $host = 'localhost';
  $password = '';
  $userz = 'root';
  $database = 'rd_folder2';
  $port = 3307; // Optional

  $connection = mysqli_connect($host, $userz, $password, $database, $port);

  if (mysqli_connect_error()) {
    echo 'di makaconnect';
  }
 /*
 create database rd_folder2;
  use rd_folder2;
  create table accounts(id int primary key auto_increment not null, email varchar(255) unique not null, password varchar(255) not null);

  create table personal_info(id int primary key auto_increment, accounts_id int, firstname varchar(100), middlename varchar(100),lastname varchar(100), birthdate date, address varchar(100), gender enum('male', 'female', 'other'), foreign key(accounts_id) references accounts(id));
 */





?>

