<?php 

require_once ("lib/db.php");

switch($_GET["action"])
{
	case "getmarkers":
		$q = "SELECT * FROM markers";
		$jsonOutput = $db->get_results($q,ARRAY_A);
		break;
		
	case "addmarker":
		$lat = $_REQUEST['lat'];
		$lng = $_REQUEST['lng'];
		$title = $_REQUEST['title'];
		$window = $_REQUEST['infoWindow'];
		
		$q = "INSERT INTO markers(lat,lng,title,window) VALUES ('$lat','$lng','$title','$window')";
		$jsonOutput = $db->query($q);
		
		break;
		
}


header('Content-type: application/json');
print(json_encode($jsonOutput));

?>