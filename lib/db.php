<?php 
/**********************************************************************
*  ezSQL initialisation for mySQL
*/
 
require_once "ezsql/shared/ez_sql_core.php";
require_once "ezsql/mysql/ez_sql_mysql.php";

if(in_array($_SERVER["HTTP_HOST"], array("localwww:8080","localhost")))
{
	$db_server = "localhost";
    $db_name = "maps";
    $db_user = "root";
    $db_password = "";
}
else
{
	$db_server = "localhost";
    $db_name = "bomba_prva";
    $db_user = "bomba_prvy";
    $db_password = "PDm4!@DV!ij6R%";
}

$db = new db($db_user, $db_password, $db_name, $db_server);
$db->show_errors = true;
$db->cache_dir = "lib/ezsql_cache";
$db->query("SET NAMES utf8");
$db->use_disk_cache = true;
$db->cache_queries = true;
$db->cache_timeout = 10/3600; // v hodinach

//$db->use_disk_cache = true;
//$db->cache_queries = true;
//$db->cache_timeout = 1;

//$db->debug_all = true;
