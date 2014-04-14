<?php

/**
 * Class DBMysqli  to manage database  using mysqli function
 * @Package Database
 * @category DBMysqli
 * @version: 0.1
 * @author Osama Salama <osama_eg@outlook.com>
 * @copyright Copyright (c) 2013, Osama Salama
 */



class DBMysqli{

/**
 * Config Array  Connection Parameters
 * @var Array 
 */
private   $Config=array();

/**
 *  Resource mysql
 * @var Resource 
 */
private $Dblink;

/**
 *  link_database String Active Database 
 * @var string 
 */
private $link_database="default";

/**
 *  Check Error true 
 * @var bool 
 */
private $Error=true;

/**
 *  table perfix 
 * @var string 
 */
private $Prefix;





/**
 * DBMysqli::getConfig() -Function To get Config selected Array 
 * @return mixed 
 */
private function getConfig($key){
return @$this->Config[$key];
}


/**
 * DBMysqli::prepareConfig() -Function To get Config By Selected Active Db
 * @return mixed
 */
private function prepareConfig(){
return $this->getConfig($this->link_database);
}


/**
 * DBMysqli::setprefix() -Function to set table prefix
 * @return mixed
 */
private function setprefix(){

$c=$this->prepareConfig();
if(is_array($c)){
if(array_key_exists('Prefix',$c)){
if($c['Prefix']!=''){
$this->Prefix=$c['Prefix'];
}
}
}
} 


/**
 * DBMysqli::checkConfig() -Function To Check argument schema
 * @return bool
 */
private function checkConfig(){
$schema= array('Host'  => 1, 'UserDb'  => 2, 'PassDb'  => 3, 'Dbname' => 4,'Prefix'=>5,'Charset'=>6);

$JConf=array_intersect_key($schema,$this->prepareConfig());
if(count($JConf) < 6){
exit("<pre><div style='color:red'>Error configuration parameters!!.</div></pre>");

} else {
return true;
}

}



/**
 *  DBMysqli::__construct() -Function To initialize configuration and active DB 
 * @param   array   $Config    configuration array
 * @param   string  $active    Active Database
 * @return bool
*/
public function __construct($Config,$active){

$this->Config=$Config;
$this->link_database=$active;
$this->connect();
$this->setprefix();
return true;
}


/**
 * DBMysqli::connect() Connect Database 
 * @return bool
 */
private function connect(){
 
if(is_array($this->prepareConfig()) && count($this->prepareConfig())>0){
$this->checkConfig();	
extract($this->prepareConfig());

$this->Dblink= @mysqli_connect($Host, $UserDb, $PassDb, $Dbname);
 
if(!mysqli_connect_errno()){
 $this->SetCharset();	
 
}
else {
exit($this->Err(mysqli_connect_error()));
}




}
else {
exit("<pre><div style='color:red'>Error configuration parameters!!.</div></pre>");

}


}


/**
 * DBMysqli::SetCharset()  set Charset for database
 * @return bool
 */
private function SetCharset(){
$c=$this->prepareConfig();
if(is_array($c)){
if(array_key_exists('Charset',$c)){
if($c['Charset']!=''){
$val=$c['Charset'];

mysqli_set_charset($this->Dblink, "$val") or $this->Err(mysqli_error($this->Dblink));
mysqli_query($this->Dblink,"SET CHARACTER SET $val") or $this->Err(mysqli_error($this->Dblink));
mysqli_query($this->Dblink,"SET NAMES $val") or $this->Err(mysqli_error($this->Dblink));
}
}
}

}


 
/**
 * DBMysqli::__destruct() Object destroyed 
 * @return bool
 */
public function __destruct()

{
return @mysqli_close($this->Dblink);
}


/**
 * DBMysqli::rowCount()  Row Count
 * @param string $table table name
 * @param string $con  where condition
 * @return int
 */
public function rowCount($table, $con=false)
{
	 
if($con!=false){
$con = 'AND '.$con;
}
$sql="SELECT COUNT( * ) as `count` FROM `".$this->Prefix.$table."` WHERE 1 ".$con;
$rowcount=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
$row = mysqli_fetch_array($rowcount, MYSQLI_BOTH) or $this->Err(mysqli_error($this->Dblink));
return $row['count'];
}


/**
 * DBMysqli::Err() function Error Show
 * @param string $msg Error Mysql
 * @return string
 */
private function Err($msg='') {
$err = "<font color='red'>
Mysqli Error Occurred<br />
Error Details:<br />
File Name: ".__FILE__."<br />
Line Number: ". __LINE__."<br />";
if($msg != '') {
$err .= "Query Says: <textarea cols='60' rows='8'>$msg</textarea>";
}
if ($this->Error) {
exit($err);
}
}




/**
 * DBMysqli::Quote() - prepare string to inserted or updated in the database
 * @param string $string
 * @return string
 */
public function Quote($string = null) {
return ($string === null) ? 'NULL' : "'" . str_replace("'", "''", $string) . "'";
}





/**
 * DBMysqli::clean() -   Clean data .:recommended filter Data through PHP Code:
 * <code>
 * <?php
 * $_POST=array_map("clean",$_POST)    $msg=clean($_POST['msg']);
 * ?>
 * </code>
 * @param string  $input - data you will INSERT 
 * @return string
*/
private function clean($input){
$input=trim($input);
$input=stripslashes($input);
$input=is_numeric($input) ? intval($input) : mysqli_real_escape_string($this->Dblink,$input);
$input=htmlspecialchars($input);
return $input; 
}

 

/**
 * DBMysqli::insertid() get Last ID insert
 * @return int
 */
public function rowID(){
if (@mysqli_affected_rows($this->Dblink)>0)
{
$id = @mysqli_insert_id($this->Dblink);
}
return (int) @$id;
}


/**
 * DBMysqli::fetchRow() Fetch One record
 * @param  string $table  table name
 * @param  string  $con   where condition
 * @param  string $order order by
 * <code>
 * <?php
 * $result=$object->fetchRow(table_name); 
 * echo $result['column_name'];
 * ?>
 * </code>
 * @return array
 */
 public function fetchRow($table,$con=false,$order=false){

if($con!=false){
$con = 'AND '.$con;
}
if($order!=false){
	$order='ORDER BY '.$order;
	}

$sql="SELECT * FROM `".$this->Prefix.$table."` WHERE 1 ". $con . $order.' limit 1';
$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
$row = mysqli_fetch_array($rs, MYSQLI_BOTH) or $this->Err(mysqli_error($this->Dblink));
if(is_array($row)){
return $row;
} 
else{
return false;
}


} 




/**
 * DBMysqli::Delete() Delete Row function From Db
 * @param string $table    table Db
 * @param string $con       where condition
 * @return bool
 */ 
public function delete($table,$con=''){

if($con!=false){
$con = 'AND '.$con;
}
$sql="delete  FROM `".$this->Prefix.$table."` WHERE 1 ".$con;
$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
if($rs==true){
return true;
}
else {
return false;
}

} 






/**
 * DBMysqli::save() -   Save  data into DB
 * recommended filter Data through PHP Code:
 * @param string $table    table Db
 * @param array $fields      fields associative array
 * @return bool
 */
 public function save($table,$fields=array()){

$check = true;
$val = '';
$names = '';
foreach ($fields as $keys=>$values) {
if ($check==true) {
$check = false;
} else {
$names .= ',';
$val .= ',';
}
$names .= $keys;
if($values=='NOW()'){
$val .=$values;
} 
else {
$val .= $this->Quote($this->clean($values));

}
}
$sql="INSERT INTO `".$this->Prefix.$table."` ($names) VALUES ($val);";
$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
if($rs==true){
return true;
}
else {
return false;
}

} 





/**
 * DBMysqli::update() -   update  query
 * @param string $table   - the table you will UPDATE in it
 * @param  array $fields   - array from the feilds and values
 * @param string $con   -   where condition
 * @return bool
 */
public function update($table,$fields=array(),$con=false){

$set = '';
$first=true;
foreach ($fields  as $keys=>$values) {
if ($first==true) {
$first = false;
} else {
$set .= ',';
}


if($values=='NOW()'){
$set .="$keys = ".$values;
} 
else if(preg_match("/^(\(.+\))$/im",$values,$match)){
$set .="$keys = ".$match[0];
}

else {
$set .= "$keys = ".$this->Quote($this->clean($values));

}

}


$sql = "UPDATE ".$this->Prefix.$table ." SET $set";

if ($con) {
$sql .= "   where 1 AND $con";
}
$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
if($rs==true){
return true;
}
else {
return false;
}


} 




/**
 * DBMysqli::Query() Free Query
 * @param string $sql  sql statment;
 * @return array
 */
public function query($sql){
if($sql==''){ exit('Error Sql Query ');}

$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
$data=array();
while($row= mysqli_fetch_array($rs, MYSQLI_BOTH)){
$data[]=$row;
}

if(is_array($data)){
return $data;
} 
else{
return false;
}

}




  
/**
 * DBMysqli::Fetch() Fetch Rows From table
 * @param string $table   Database table ;
 * @param string $con Condtion statment;
 * @param string $order order by statment;
 * @return Array
*/ 

public function fetch($table,$con=false,$order=false){
 if($table==''){
	 exit('Error:Table missing');
	 }
 if($con!=false){
$con = 'AND '.$con;
}

 if($order!=false){
$order = 'order by '.$order;
}
$sql="select *  FROM `".$this->Prefix.$table."` WHERE 1 ".$con .$order ;
$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));

while($row=mysqli_fetch_array($rs, MYSQLI_BOTH)){
$rows[]=$row;
}
if(is_array($rows)){
return $rows;
} 
else{
return false;
}
 
} 
  

 

/**
 * DBMysqli::fetchAll() Execute Query From Db
 * @param  array $fields fields name or *;
 * @param string $table table name
 * @param string $con where condition or having
 * @param array $join join table rule
 * @param array  $group group statment
 * @param string $order order by
 * @param  int     $start  page number when using limit
 * @param  int     $limit   record limit from database
 * <code>
 * <?php
 * $result=$object->fetchAll('*','table name',false,false,false,false,false,flase); 
 *foreach($result as $row){
 *  echo $row['index or column name']
 *                                            }
 * ?>
 * </code>
 * @return array
 */ 
public function fetchAll($fields,$table,$con=false,$join=false,$group=false,$order=false,$start=false,$limit=false)
{
if(is_array($fields)){
$fields=implode(",",$fields);
} else{
$fields="*";	
}

if($table==''){
exit('Error:Table Missing.');
}

if($con!=false){
$con="WHERE 1 AND " .$con;
}

if(is_array($join)){
$join=implode("  ",$join);
}	

if($group){
$group="group by ".$group;
}

if($order){
$order=" order by  ".$order;
} 

if($start==false){
$from=0;
}
else if($start==1){
$from=$start-1;
}
else{
$from=$start;
}

if($limit!=false){
$limiter="limit ".$from.",".$limit;
} else{
$limiter='';
}

$sql="SELECT $fields FROM `".$this->Prefix.$table."` $join $con $group $order $limiter";
$rs=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
$data=array();
while($row= mysqli_fetch_array($rs, MYSQLI_BOTH)){
$data[]=$row;
}
if(is_array($data)){
return $data;
} 
else{
return false;
} 


}




 /**
 * DBMysqli::loadCSS() Load CSS for pagination
 * @return string
 */
private function loadCSS(){
$css="<style>";
$css.="
.pagination{
width:80%;
border:1px #D7D7D7 solid;
margin:0 auto;
height:25px;
-webkit-border-radius: 15px;
-moz-border-radius: 15px;
border-radius: 15px;
font-family:Tahoma, Geneva, sans-serif;
font-size:12px;
font-weight:normal;
padding-top:10px;
text-decoration:none;
}
.pagination a{
text-decoration:none;
}	
.pagination_right{
margin-left: auto;
margin-right: auto;
float:right;
padding-right:10px;

vertical-align:middle ;
width:25%;
text-align:center;
}	

.pagination_center{
width:50%;
margin:0 auto;
text-align:center;
}
.pagination_left{

margin-left: auto;
margin-right: auto;
float:left;
padding-left:10px;
width:25%;
text-align:center;
}		

.pagenum{
-webkit-border-radius: 15px;
-moz-border-radius: 15px;
border-radius: 15px;
color:#F30;
border:1px #666666 solid;
width:40px;
padding:5px;
text-decoration:none;
}	

.pages{
-webkit-border-radius: 15px;
-moz-border-radius: 15px;
border-radius: 15px;
border:1px #666666 solid;
padding:4px;
}";
$css.="</style>";
return $css;
}



/**
 * DBMysqli::pagination()   pagination database record
 * @param int $page Type:integer page number from url
 * @param int $limit Type:integer limit record
 * @param string link Type:string  additional url
 * @param  int $count Type:integer  record count 
 * @param  string $nextpage   Title of nextpage
 * @param  string   $prevpage   Title of prevpage
 * @param string    $firstpage  Title of firstpage
 * @param string    $lastpage  Title of lastpage
 * @return string
 */
public function pagination($page,$limit,$link,$count,$nextpage,$prevpage,$firstpage,$lastpage)
{
echo $this->loadCSS();

if($page==null){
$page=1;
}
$pages=ceil($count/$limit);
if(($page > $pages) || ($page <=0)){
die("No Recored More");
}
$html='<div class="pagination" >';
$html.='<div class="pagination_right">';
$one=1;
$html.= "&nbsp;&nbsp;&nbsp;<span class=\"pages\"><a href=?page=".$one. $link.">$firstpage</a></span>&nbsp;\n";

if($page <$pages){
$next=$page+1;
$html.= "&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"pages\"><a href=?page=".$next.$link.">$nextpage</a></span>&nbsp;\n";
} else{
$html.= "<span class=\"pages\">$nextpage</span>&nbsp;\n";
}

$html.='</div>';
$html.='<div class="pagination_left">';
if($page >1){
$prev=$page-1;
$html.= "&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"pages\"><a href=?page=".$prev.$link.">$prevpage</a></span>&nbsp;\n";
} else{
$html.= "<span class=\"pages\">$prevpage</span>&nbsp;\n";

}

$html.= "&nbsp;&nbsp;&nbsp;<span class=\"pages\"><a href=?page=".$pages.$link.">$lastpage</a></span>&nbsp;\n";

$html.='</div>';

$html.='<div class="pagination_center">';

$froms= ($page-1) * $limit;
$maxpage =  $page + 10 ;
for ($i = $page ; $i <= $maxpage && $i <= $pages ; $i++)
{
if($i > 0)
{
$nextpag = $limit*($i-1);
if ($nextpag == $froms)
{
$html .= "<span class=\"pagenum\" ><b>( $i )</b></span>&nbsp;\n";
}
else
{
$html.= "<span class=\"pagenum\"><a href=?page=".$i.$link.">$i</a></span>&nbsp;\n";
}
}
}


$html.='</div>';
$html.='</div>';
return $html;
}





/**
 * DBMysqli::FormatSize()   function To Format size  
 * @param int $size  File size
 * @return string
 */  
public  function FormatSize($fileSize)
{

$byteUnits = array(" GB"," MB"," KB"," bytes");

if($fileSize >= 1073741824)
{
$fileSize = round($fileSize / 1073741824 * 100) / 100 . $byteUnits[0];
}
elseif($fileSize >= 1048576)
{
$fileSize = round($fileSize / 1048576 * 100) / 100 . $byteUnits[1];
}
elseif($fileSize >= 1024)
{
$fileSize = round($fileSize / 1024 * 100) / 100 . $byteUnits[2];
}
else
{
$fileSize = $fileSize . $byteUnits[3];
}
return $fileSize;
}
 




/**
 * DBMysqli::databaseSize()     Size of database  
 * @return string
 */
public function databaseSize(){
$_db=$this->prepareConfig();
$dbname=$_db['Dbname'];
$sql="SELECT table_schema '$dbname',
sum( data_length + index_length )  'Data Base Size in MB'
FROM information_schema.TABLES
GROUP BY table_schema" ; 
$rec=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
$row= mysqli_fetch_array($rec, MYSQLI_BOTH);
return $this->FormatSize($row[1]);
	}





/**
 * DBMysqli::backUp()   backUp database 
 * @param string $format :  IN ->For inline  OUT->For download
 * @See   DBMysql::download() 
 * @return Boolean
 */
public function backUp($format='IN'){
	
$_db=$this->prepareConfig();
 
$dbname=$_db['Dbname'];
$sql="SHOW TABLES from $dbname";
 
 $html = '#backUp Database MySqli' . "\n" .
               '#' . "\n" .
               '# Database Backup For ' .$_SERVER['HTTP_HOST']. "\n" .
               '# Copyright (c)  Osama_eg@outlook.com     ' . date('Y') . ' ' .$_SERVER['HTTP_HOST']. "\n" .
               '#' . "\n" .
               '# Backup Date: ' . date("Y/m/d t-m-s") . "\n\n";
			    
$rs1=mysqli_query($this->Dblink,$sql)  or $this->Err($sql.mysqli_error($this->Dblink));
while($row=mysqli_fetch_array($rs1, MYSQLI_BOTH)){
$tables[]=$row[0];
}	


$fields=array();
if(is_array($tables) && count($tables) > 0 ){

for($i=0;$i<count($tables);$i++){
$html.= 'Drop TABLE  IF EXISTS `' .$tables[$i]. '`;' . "\n\n\t" ;

$sql2="SHOW CREATE TABLE $tables[$i]";
$rs2=mysqli_query($this->Dblink,$sql2)  or $this->Err($sql2.mysqli_error($this->Dblink));
$row2=mysqli_fetch_array($rs2, MYSQLI_BOTH);

$html.=$row2[1].";\n\t\n";

}
}
else {
exit("Error:No table exist in Database");
}

foreach( $tables as $key => $val){
$sql3="desc {$tables[$key]}";
$rs3=mysqli_query($this->Dblink,$sql3)  or $this->Err($sql3.mysqli_error($this->Dblink));

$first=true;
$comma='';
while( $row3=mysqli_fetch_array($rs3, MYSQLI_BOTH)){

if($first==true){
$first=false;
} else{
$comma=',';
}
@$fields[$val].=$comma.'`'.$row3[0].'`' ;
}
}

foreach( $tables as $key => $val){
$sql4="select * from   $val";
$rs4=mysqli_query($this->Dblink,$sql4)  or $this->Err($sql4.mysqli_error($this->Dblink));


while($row4=mysqli_fetch_array($rs4, MYSQLI_BOTH)){
$c=explode(",",$fields[$val]);
$values="";
$first=true;
$comma='';
for($j=0;$j<count($c);$j++){

if($first==true){
$first=false;
} else{
$comma=',';
}

$values.=$comma."'".addslashes($row4[$j])."'";
}

$html.= " Insert into `$val` ($fields[$val]) Values($values);\n\t";
}
}


$this->download($html,$format);

return true;
	
}


/**
 * DBMysqli::Download() Download Sql File.......
 * @param string $buffer   Sql data ;
 * @param string $view   IN =>For Inline, OUT =>For download
 * @return string 
 */ 
public function download($buffer,$view="OUT")
{

$name=time().'.txt';
switch($view){
//Download
case "OUT":
if (ob_get_contents()) {
$this->Err('Some data has already been output, can\'t send Sql file');
}
header('Content-Description: File Transfer');
if (headers_sent()) {
$this->Err('Some data has already been output to browser, can\'t send Sql file');
}
header("Cache-Control: public, must-revalidate, max-age=0"); // HTTP/1.1
header("Pragma: public");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
// force download dialog
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream", false);
header("Content-Type: application/download", false);
// use the Content-Disposition header to supply a recommended filename
header('Content-Disposition: attachment; filename="'.basename($name).'";');
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($buffer));
print($buffer);
break;

//inline
case "IN":


//Send to standard output
if (ob_get_contents()) {
$this->Err('Some data has already been output, can\'t send Sql file');
}
if (php_sapi_name() != 'cli') {
//We send to a browser
header('Content-Type: text/plain');
if (headers_sent()) {
$this->Err('Some data has already been output to browser, can\'t send Sql file');
}
header("Cache-Control: public, must-revalidate, max-age=0"); // HTTP/1.1
header("Pragma: public");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");	
header('Content-Length: '.strlen($buffer));
header('Content-Disposition: inline; filename="'.basename($name).'";');
}
print($buffer);


break;
}
 } 



/**
 * DBMysqli::redirect() function To Redirect
 * @param $string $address    Redirect Url
 * @return bool
 */
public function redirect($address){
if(!headers_sent()){
header("location:$address");
return true;
}  
else {
echo "<script type=\"text/javascript\">window.location.href=$address</script>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=$address\">";
return true;
}
}



/**
 * DBMysqli::generateKey()  function to genrate key
 * @return string   
 */
public function generateKey(){
$seed=(double) microtime() * 1000000;
srand($seed);
return rand();
} 

 
/**
 * DBMysqli::randomkey() function To get randomkey
 * @param  int $len     key length   
 * @return string
 */   
public  function randomPassword($len){
$key=null;
$pattern="0123456789abcdefghijklmonpqrstyvwxyz!@%&()*/-";
 for($i=0;$i<$len;$i++){
$key.=$pattern{rand(0,44)};
 }
 return $key;
 }
 
  
 /**
 * DBMysqli::ShowIP() get Real IP Adress
 * @return string 
 */ 

public function showIP(){
if(getenv('HTTP_X_FORWARDED_FOR')){
$ip=getenv('HTTP_X_FORWARDED_FOR');
}
elseif (getenv('HTTP_CLIENT_IP')){
$ip=getenv('HTTP_CLIENT_IP');
}
elseif(getenv('REMOTE_ADDR')) {
$ip=getenv('REMOTE_ADDR');
} 
 else {
$ip=$_SERVER['REMOTE_ADDR']; 
 }
 return $ip;
}   
 




/**
 *  DBMysqli::newEmail() Send Mail by Mail Function...
 * @param string $address   Mail Address 
 * @param string $subject    Subject message
 * @param string $message    content Message
 * @param string $from   mail send from it
 * @return bool
 */ 
public function newEmail($to,$subject,$message,$from){
 
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8'. "\r\n";
$headers .= 'From: '.$from .' Reply-To: '.$from.'\r\n X-Mailer: PHP/'. phpversion();
if(@mail($to,$subject,$message,$headers)){
return true;
}else{
return false;
} 
 
}   



/**
 * DBMysqli::File_extension()    function get File Exetention.......
 * @param string $filename   Filename &path file;
 * @return string 
 */
public function File_extension($filename)
{
$path_info = @pathinfo($filename);
return $path_info['extension'];
}




//end class	
}




?>