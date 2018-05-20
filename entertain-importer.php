#!/usr/bin/php
<?php 
require_once 'lib/Properties.php';

Properties::init('config.properties');


//dbcon
$host=Properties::get('db_host');
$db=Properties::get('db_name');
$username=Properties::get('db_user');
$password=Properties::get('db_pass');
$pls_link=Properties::get('pls_link');
$rtp_proxy=Properties::get('pls_rtpproxy');
$nodeid=Properties::get('pls_nodeid');

$dsn= "mysql:host=$host;dbname=$db";

try{
 // create a PDO connection with the configuration data
 $conn = new PDO($dsn, $username, $password);

 // display a message if connected to database successfully
 if($conn){
 echo "Connected to the $db database successfully!\n";
        }
}catch (PDOException $e){
 // report error message
	echo $e->getMessage();
	exit;
}


// download

try {
	$playlist=file_get_contents($pls_link);
	$fn = '/tmp/tentertain'.date('U').'.pls';
	file_put_contents($fn,$playlist);
	Properties::init($fn);
	unlink($fn);
	// what is the video node id?
	if (!$nodeid) {
		$sql="select id from mt_cds_object where parent_id=(SELECT id FROM mt_cds_object where dc_title='Video' and parent_id=0) and dc_title='T-Entertain'";
		try {
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$nodeid=$row['id'];
				echo "found T-Entertain node with ID $nodeid\n";
			} else {
				echo "Cannot find node name T-Entertain as subelement of Video. Please create manually with web interface first\n";
				exit;
			}
	
		} catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}
	}
	$sql="delete from mt_cds_object where parent_id=:nodeid ";
	try {
		$stmt = $conn->prepare($sql);
		$stmt->execute(array(':nodeid'=>$nodeid));
		echo "deleted ".$stmt->rowCount()." lines\n";
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}


	for ($i=1;$i<=250;$i++) {
		$url =   Properties::get('File'.$i);
		$title = Properties::get('Title'.$i);
		if (!$url || !$title) {
			continue;
		}
		
		$t = explode(' ',$title);
		$nr=array_shift($t);
		$nr=intval(substr($nr,1,-1)); //removes the brackets
		$nr = sprintf('%04d',$nr);
		$title=join(' ',$t);
		$title=$nr.' '.$title;

		$title=mb_convert_encoding($title,"ISO-8859-1","auto");

		//update with rtp_proxy
		if ($rtp_proxy) $url = str_replace('rtp://@',$rtp_proxy,$url);

		$description = 'dc%3Adescription='.urlencode($title);
		$resources   = '0~protocolInfo=http-get%3A%2A%3Avideo%2Fmpeg2%3A%2A~~';
		echo "Inserting $title => $url\n";

		$insert = "INSERT into mt_cds_object(
			parent_id,
			object_type,
			upnp_class,
			dc_title,
			location,
			metadata,
			resources,
			update_id,
			mime_type,
			flags
		) VALUES(
			:nodeid,
			10,
			'object.item',
			:title,
			:url,
			:description,
			:resources,
			0,
			'video/mpeg2',
			1
		)";
		try {
			$stmt = $conn->prepare($insert);
			$result = $stmt->execute(array(
				':nodeid'      => $nodeid,
				':title'       => $title,
				':url'         => $url,
				':description' => $description,
				':resources'   => $resources
			));

		} catch (PDOException $e) {
			echo $e->getMessage();
			exit;
		}
	}	

} catch (Exception $e) {
	echo $e->getMessage();
	exit;

}



?>
