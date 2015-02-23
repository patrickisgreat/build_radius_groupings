<?php
include('dBConnectClass.php');
include('postClass.php');
include('clusterClass.php');
include('geoCodeClass.php');
include('getDataClass.php');

//$db = new DbConnect('privatelounge.blacqube.org', 'pbennett', 'n1njAst@r', 'vbulletin');
$db = new DbConnect('162.243.217.180', 'pbennett', 'swacuGaKur2j', 'vbulletin');
$geoCode = new GeoCode($db);
$cluster = new Cluster(268435456, 85445659.4471, $db);
$post = new Post($db, '/json_test/test.json');
$getData = new GetData($db, $geoCode);

//get the data
$freshData = $getData->getData();
//check it for anything missing and replace country name with ISO
$checkedData = $geoCode->checkData($freshData);
//geocode the good data add the values to the database
//once this works figure out how to make it incremental
foreach ($checkedData as $k=>$v) {
	//geocode it
	$geocode = $geoCode->geocode($v['field22'], $v['field61'], $v['field19'], $v['field23']);

	//check to make sure it got geocoded and then update the datbase
	if ($geocode['lat'] !== null) {
		$update = $geoCode->updateDb($geocode, $v['userid']);
	} 
}

//cluster everything -- should overwrite
$cluster->build();

$processedData = $getData->getPostData();

//send to SOLR
$post->post($processedData);

?>