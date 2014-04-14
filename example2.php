<?php
require_once "Database.php";

$config['default']['Host']="localhost";
$config['default']['UserDb']="root";
$config['default']['PassDb']="";
$config['default']['Dbname']="cms";
$config['default']['Prefix']="";
$config['default']['Charset']="";

/*
choose MYSQL  or MYSQLi 
*/
$DB=Database::getInstance("mysqli",$config);

//insert data=================================
/*
$data=array(
'ID'=>'1',
'name'=>'ali mohammed ali',
'dates'=>'NOW()'
);
$DB->save('table name',$data);
*/


//update=========================================

/*
$data=array(
'name'=>'ali mohammed ali',
'counter'=>'(counter+1)'  //you can use this to increment data in column .
);
$DB->update('table name',$data,"ID='1' and email='tes@te.com' ");
*/


//free sql=============================
/*
$sql="select .......................";
$rs=$DB->query($sql);
foreach($rs as $row){
	echo $row['columnname or index'];
	}
*/
?>