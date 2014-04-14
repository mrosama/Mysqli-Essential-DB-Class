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

 /* 
//fetchAll  and pagination
$fileds="*"; // you can use ( * ) or array contains column array('ID','email','name');
$tablename="orders"; //tablename
$con=false;  //where condition  like  category='sport' or category='sport'  :if no condition type false
$join=false;  //if no join type false or array like array("left join cm_cat on cm_articles.cat=cm_cat.ID")
$group=false; //    :if no group type false     ex.      like "cm_articles.ID";
$order='ID asc'; //if no order type false
$start=false;  // if you are using pagination write page number if not type false
$limit=false; // all record type false if not type record limit
	
	$rs1=$DB->fetchAll($fileds,$tablename,$con,$join,$group,$order,$start,$limit);
 foreach($rs1 as $row){
	 echo $row['ID']."==".$row['name']."<br/>\n";
	 }

	 output
1==osama salama
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
	 */
/*
example inline fetch
$rs1=$DB->fetchAll('*','orders',false,false,false,false,false,flase);
*/	 
	 

//use in pagination
echo "<hr/>";
/*
if(!isset($_GET['page']) or $_GET['page']==0){
	$page=1;
	}
	else {
	$page=$_GET['page'];	
		}
$limit=15; //for record in page	
$rs2=$DB->fetchAll('*','orders',false,false,false,false,$page,$limit);

 foreach($rs2 as $row){
	 echo $row['ID']."==".$row['name']."<br/>\n";
	 }


 $rowcount=$DB->rowCount("orders");
 $link='&cat=5'; // like $link="&cat=5"; like
 
echo $DB->pagination($page,$limit,$link,$rowcount,'Next Page','Prev Page','First Page','Last Page');

	*/