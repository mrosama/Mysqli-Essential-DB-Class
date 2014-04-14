<?php
require_once "Database.php";

$config['default']['Host']="localhost";
$config['default']['UserDb']="root";
$config['default']['PassDb']="";
$config['default']['Dbname']="cms";
$config['default']['Prefix']="";
$config['default']['Charset']="";

//backup

$DB=Database::getInstance("mysqli",$config);
$DB->backUp('IN');
/*
output like
backUp Database MySqli
#
# Database Backup For localhost
# Copyright (c)  Osama_eg@outlook.com     2013 localhost
#
# Backup Date: 2013/03/25 31-03-04

Drop TABLE  IF EXISTS `cat`;

	CREATE TABLE `cat` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cat` int(11) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
	
Drop TABLE  IF EXISTS `members`;

	CREATE TABLE `members` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `dat` datetime DEFAULT NULL,
  `counter` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
	
Drop TABLE  IF EXISTS `orders`;

	CREATE TABLE `orders` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
	
 Insert into `cat` (`ID`,`cat`,`name`) Values('1','1','sport');
	 Insert into `cat` (`ID`,`cat`,`name`) Values('2','2','egypt');
	 Insert into `cat` (`ID`,`cat`,`name`) Values('3','3','news');
	 Insert into `cat` (`ID`,`cat`,`name`) Values('9','4','games');
	 Insert into `cat` (`ID`,`cat`,`name`) Values('10','4','gamess');
	 Insert into `members` (`ID`,`name`,`email`,`dat`,`counter`) Values('1','osama salama','osama_eg@outlook.com','2013-03-22 17:57:22','4');
	 Insert into `members` (`ID`,`name`,`email`,`dat`,`counter`) Values('2','ali','ali@outlook.com','2013-03-22 17:47:54','1');
	 Insert into `members` (`ID`,`name`,`email`,`dat`,`counter`) Values('3','ali','ali@outlook.com','2013-03-22 17:47:54','1');
	 Insert into `members` (`ID`,`name`,`email`,`dat`,`counter`) Values('4','ali','ali@outlook.com','2013-03-22 17:47:54','1');
	 Insert into `members` (`ID`,`name`,`email`,`dat`,`counter`) Values('5','ali','ali@outlook.com','2013-03-22 17:47:54','1');
	 Insert into `members` (`ID`,`na
*/

//
//get size database
echo $DB->databaseSize();
//output
/*
48 KB
*/
?>