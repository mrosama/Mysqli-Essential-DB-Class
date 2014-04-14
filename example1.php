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


 $result=$DB->fetchRow('members',"ID ='1'");
 
 $data='<pre> ID : '.$result['ID']."<br/>";
 $data.='name : '.$result['name']."<br/>";
 $data.='email : '.$result['email']."<br/></pre>";
print($data);
/*
output
 ID : 1
name : osama salama
email : osama_eg@outlook.com
*/
 
 
 
 
 //different database connetion
$forum['vb']['Host']="localhost";
$forum['vb']['UserDb']="root";
$forum['vb']['PassDb']="";
$forum['vb']['Dbname']="ora";
$forum['vb']['Prefix']="";  //table prefix
$forum['vb']['Charset']="utf8";

/*
choose MYSQL  or MYSQLi 
active other database by key  vb


$DB2=Database::getInstance("mysqli",$forum,'vb');
 $result2=$DB2->fetchRow('cat'," ID='1' ");
  $data2='<pre> ID : '.$result2['ID']."<br/>";
 $data2.='name : '.$result2['name']."<br/></pre>";

print($data2);
 */
 
 //fetch data
 /*
  $result1=$DB->fetch('orders',"ID != '1'",'ID asc');
 foreach($result1 as $row){
	 echo $row['ID']."==".$row['name']."<br/>\n";
	 }
	
	output
	2==ali ahmed ali
3==Tamer sami
4==Mohammed ali
5==ahmed mahmmod
6==ali elsied ali
7==walled sami
8==waeel helal
9==joen apo
10==mostafa hafez
11==alaa elsedd ali
12==abdo hablex
13==galall
14==hani
15==dnsadn
16==bjkhbk*/
	  
 ?>
 