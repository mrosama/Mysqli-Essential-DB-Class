<?php


/**
 * Class DatabaseTo switch between mysql &mysqli 
 * @Package Database
 * @version: 0.1
 * @author Osama Salama <osama_eg@outlook.com>
 * @copyright Copyright (c) 2013, Osama Salama
 */



class Database{


/**
* Instance object from class
* @var object 
*/
private static $_Instance;


/**
* getInstance build object
* @param string $type  type database mysql or mysqli
* @param array $config  configuration array
* @param string $active active database
* @return object 
*/

public static function getInstance($type,$config,$active='default') 
{
$c=self::load($type,$config,$active);
self::$_Instance =$c;
return self::$_Instance;
}


/**
*  load       include  selected class 
* @param string $type  type database mysql or mysqli
* @param array $config  configuration array
* @param string $active active database
* @return object 
*/
public static function load($type,$config,$active){
switch($type){
case "mysql":
self::checkLib("mysql");
require_once  'Drivers/Mysql.class.php';
$Mysql =new DBMysql($config,$active);
return  $Mysql;
break;


case "mysqli":
self::checkLib("mysqli");
require_once  'Drivers/Mysqli.class.php';
$Mysqli =new DBMysqli($config,$active);
return  $Mysqli;
break;
default:
exit("<pre><div style='color:red'>This File is not available on the server!!.</div></pre>");
} 
}


/**
*  checkLib      check _loaded_extensions
* @param string $ex  extension name
* @return bool 
*/
public static function checkLib($ex) {
if  (in_array ($ex, get_loaded_extensions())) {
return true;
}
else{
exit("<pre><div style='color:red'>This extension is not available on the server!!.</div></pre>");
}
}
}
?>