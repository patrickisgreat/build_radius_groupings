<?php
include('dBConnectClass.php');
include('postClass.php');
include('clusterClass.php');
include('geoCodeClass.php');
include('getDataClass.php');

$db = new DbConnect('database.com', 'username', 'password', 'usedb');
var_dump($db);
$geoCode = new GeoCode($db);
$post = new Post($db, '/json_test/test.json');
$getData = new GetData($db, $geoCode);
$cluster = new Cluster(268435456, 85445659.4471, $db, $getData);

//get the data
$freshData = $getData->getData();
//check it for anything missing and replace country name with ISO
$checkedData = $geoCode->checkData($freshData);
//geocode the good data add the values to the database
//once this works figure out how to make it incremental
foreach ($checkedData as $k=>$v) {
	//geocode it
	$geocode = $geoCode->geocode($v['field22'], $v['field61'], $v['field19'], $v['field23']);
	var_dump($geocode);
	echo "<br />";
	//check to make sure it got geocoded and then update the datbase
	if ($geocode['lat'] !== null) {
		echo "updating db";
		echo "<br />";
		$update = $geoCode->updateDb($geocode, $v['userid']);
	} 
}

//cluster everything -- should overwrite
$cluster->build();

$processedData = $getData->getPostData();
//send to app locale
$post->post($processedData);

?>