<?php
include 'dbconnect.php';
include 'function.php';

$datatype = $_GET['data'];

$type = strtolower($datatype);

if ($type == "addtask") {
	$selClients = selActive("hosts"); 			# Clients hosts table : hosts
	$selStorage = selActive("Storage"); 		# Storage Table : Storage
	$selBackend = selActive("StorageBackend"); 	#Storage Backend Table : StorageBackend
	$selTestType = selTestType();
	$selUsers = selUserList();

	$data = array (
		'hosts' => json_decode($selClients),
		'storage' => json_decode($selStorage),
		'backend' => json_decode($selBackend),
		'testtype' => json_decode($selTestType),
		'users' => json_decode($selUsers)
	);

	echo json_encode($data);
}
elseif ($type == "addstorage") {

	$selStorageType = selStorageType(); # Storage Table : Storage
	print $selStorageType;
	//echo json_encode($data);

}
elseif ($type == "addclient") {
	#print "<h1> You have selected ". $datatype ." </h1>";
	$selClients = selActive("hosts"); # Clients hosts table : hosts
}
elseif ($type == "addStorage_form") {
	$results = selStorageType();
	echo $results;
}
elseif ($type == "addbackend") {
	$results = selBackendType();
	echo $results;
}
elseif ($type === "clientsid") {
	$clientsName = selActiveTaskClients($_GET['ids']);
	#echo "CLIENTS " . $clientsName."<be>";
	echo $clientsName;
} else
	if ($type == "removetask") {
		echo "<h4>Removing Task " . $_GET['ids'] . "</h4>";
		$status = removeTask($_GET['ids']);
		echo $status;
	}
?>
