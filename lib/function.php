<?php

#require_once '../includes/Logger.class';
#include 'includes/Logger.class';
#include_once('includes/' . $class . '.class.php');

#$logger = new Logger("/tmp/taskAutomation/","taskAutomation");
#echo $logger;
#$logger->info("this is a test);
include 'KLogger.php';
$log = new KLogger ( "/tmp/log.txt" , KLogger::DEBUG );
$log->LogInfo("Internal Query Time: $time_ms milliseconds");

function displayPageInfo($data){
    print "<label style=\"color:white;\">INFO DATA <b style=\"color:red;\">".$data. "</b></label><br>";
}

function tab($tab) {
  for ($i = 0; $i <= $tab; $i++) {
    echo "\t";
  }
}
function nl($nl) {
	for ($i = 0; $i <= $nl; $i++) {
		echo "\n";
	}
}
function br($br) {
	for ($i = 0; $i <= $br; $i++) {
		echo "<br/>";
	}
}
function nbsp($count) {
	for ($i = 0; $i < $count; $i++) {
		print "&nbsp;";
	}
}
function logFileWrite($type,$data){  #INFO/WARNING/DEBUG Test dead

  $filename = "/tmp/AT/autoTA.log";
  
  if (!file_exists($filename)) {
    $fp = fopen($filename,"w"); 
    fwrite($fp,"0"); 
    fclose($fp);   
  } 
  
  if (is_writeable($filename)) {
    if (!$handle = fopen($filename , 'a')) {
      echo "Cannot Open File ($filename)";
      exit;
    }
    $nowTime = date("Y-m-d H:i:sa");
    $tt = "[ ".$nowTime." ] [ ".$type." ] ";
    if (fwrite($handle, $tt." ".$data)=== FALSE ) {
      #$logger->error('Cannot Write to (',$filenanme,')');
      exit;
    }
  }
}
function selStorageByType($stgName) {

  $queryStorageType =  "    SELECT StorageType.type ";
  $queryStorageType .= "      FROM automation.Storage Storage ";
  $queryStorageType .= "INNER JOIN automation.StorageType StorageType ON (Storage.type = StorageType.id)";
  $queryStorageType .= "     WHERE (Storage.name = \"".$stgName."\")";
  $result = mysql_query($queryStorageType) or die("Error in the query " . $queryStorageType. " <br>" . mysql_error());

  $stgType = mysql_fetch_array($result, MYSQL_ASSOC) ;
  
  return $stgType['type'];
}

function selActive($table) {
        $storageResults = array();
        #$logger->info($table);
	if ( $table == "Storage")  {
		$sqlQ = "SELECT id,name FROM " . $table . " WHERE active   = 0";
		$resSTGName = mysql_query($sqlQ) or die("Error in the query " . $sqlQ . " <br>" . mysql_error());
		while ($r = mysql_fetch_array($resSTGName)) {
  #                $logger->info($r);
			$data = getStorageInfo($r);
                        array_push($storageResults, $data);
		}
  #              $logger->info($storageResults);
                $data = json_encode($storageResults);
                return $data;
	} else {
		$sqlQ = "SELECT * FROM " . $table . " WHERE active   = 0";
	        $data = getjsonfromSqlArray($sqlQ, MYSQLI_ASSOC);
	    return $data;
	}
}
function selTestType() {
	$QueryType = "SELECT id,name FROM TestType ";
	$data = getjsonfromSqlArray($QueryType, MYSQLI_ASSOC);
	return $data;
}
function selUserList() {
	$QueryUsers = "SELECT * FROM users ";
	$data = getjsonfromSqlArray($QueryUsers, MYSQLI_ASSOC);
	return $data;
}
function selStorageType() {
	$QueryStorType = "SELECT * FROM StorageType";
	$data = getjsonfromSqlArray($QueryStorType, MYSQLI_ASSOC);
	return $data;
}
function selActiveTaskClients($ids) {
	$queryClients = "SELECT name FROM hosts WHERE id IN ( " . $ids . " )";
	$resClients = mysql_query($queryClients) or die("Error in the query " . $queryClients . " <br>" . mysql_error());
	$cli_name;
	$count = 0;

	while ($r = mysql_fetch_array($resClients)) {
		if ($count <= 0) {
			$cli_name .= $r['name'];
		} else {
			$cli_name .= "," . $r['name'];
		}
		$count++;
	}
    print ("<label> <b>".$cli_name."</b></label>");
	return $cli_name;
}
function selBackendType() {
	$QueryStorType = "SELECT * FROM StorageBackendType";
	$data = getjsonfromSqlArray($QueryStorType, MYSQLI_ASSOC);
	return $data;
}
function selActiveTask() {
#	$selActiveTask = "SELECT  runningTask.id,
#	                            runningTask.storageSVC,
#	                            runningTask.storageRaceMQ,
#	                            users.name AS user_name,
#	                            TestType.name AS test_type,
#	                            Storage.name AS storage_name,
#	                            StorageBackend.name AS backend_name,
#	                            runningTask.taskDate,
#	                            runningTask.clientsName,
#	                            runningTask.active AS stat
#	                      FROM  (((automation.runningTask runningTask
#	                INNER JOIN automation.users users                   ON (runningTask.userName = users.id))
#	                INNER JOIN automation.TestType TestType             ON (runningTask.testUsed = TestType.id))
#	                INNER JOIN automation.Storage Storage               ON (runningTask.storageName = Storage.id))
#	                INNER JOIN automation.StorageBackend StorageBackend ON (runningTask.backendName = StorageBackend.id)
#	                WHERE (runningTask.active = 0)";
        $selActiveTask = "SELECT DISTINCT StorageBackend.name AS backendName,
                                          StorageType.type AS storageType,
                                          RaceMQBuilds.build AS RaceBuild,
                                          runningTask.taskDate,
                                          runningTask.storageSVC,
                                          runningTask.clientsName,
                                          runningTask.testUsed,
                                          runningTask.id,
                                          users.name AS user_name,
                                          Storage.name AS storage_name
                                     FROM ((((automation.Storage Storage
                               INNER JOIN automation.StorageType StorageType        ON (Storage.type = StorageType.id))
                               INNER JOIN automation.runningTask runningTask        ON (runningTask.storageName = Storage.id))
                               INNER JOIN automation.StorageBackend StorageBackend  ON (runningTask.backendName = StorageBackend.id))
                               INNER JOIN automation.RaceMQBuilds RaceMQBuilds      ON (runningTask.RaceMQBuildsID = RaceMQBuilds.id))
                               INNER JOIN automation.users users                    ON (runningTask.userName = users.id)
                                    WHERE (runningTask.active = 0 )";


	$QueryStorType = "SELECT * FROM `runningTask` WHERE `active` = 1";
        #print "<label style=\"color:white;\">Active Task Data <b style=\"color:red;\">".$selActiveTask."</b></label><br>";
	$resultsActiveTask = mysql_query($selActiveTask) or die("<b>ERROR : </b> Unable to retrive active task results" . mysql_error());
	$data = getJsonActiveTasks($selActiveTask, MYSQLI_ASSOC);
        

	#$stgJdecode = json_decode($data);
	#echo "<h4>stgJdecode " print_r($stgJdecode)."<h4>";
	return $data;
}
function getJsonActiveTasks($sql, $arrType) {
	$res = mysql_query($sql) or die("Error in the query " . $sql . " <br>" . mysql_error());
        #print "<label style=\"color:white;\">Active Task Data <b style=\"color:red;\">".var_dump($res)."</b></label><br>";
	$rowJson = array ();
	while ($r = mysql_fetch_assoc($res)) {
		$cliIDS = $r['clientsName'];
                $stgNames = explode("," , $r['storage_name']);
        
                foreach ($stgNames as $stgName) {
                
                    $stgType = selStorageByType($stgName);
                    $r['type'] = $stgType;
	                //print "<label style=\"color:white;\">Storage ID: <b style=\"color:red;\">". $stgName . "</b></label><br>";
                }
		$unixtime = $r['taskDate'];
		$r['clientsName'] = selActiveTaskClients($cliIDS);
		$r['taskDate'] = date("M j Y - G:i:s ", $unixtime);

		$rowJson[] = $r;
	}
	#echo "<h2>json value " .print_r($rowJson)."</h2>";
	return json_encode($rowJson);
}
function getjsonfromSqlArray($sql, $arrType) {
	$res = mysql_query($sql) or die("Error in the query " . $sql . " <br>" . mysql_error());
	$rowJson = array ();
	while ($r = mysql_fetch_assoc($res)) {
		$rowJson[] = $r;
	}
	return json_encode($rowJson);
}

function array2json($arr) {
	if (function_exists('json_encode'))
		return json_encode($arr); //Lastest versions of PHP already has this functionality.
	$parts = array ();
	$is_list = false;

	//Find out if the given array is a numerical array
	$keys = array_keys($arr);
	$max_length = count($arr) - 1;
	if (($keys[0] == 0) and ($keys[$max_length] == $max_length)) { //See if the first key is 0 and last key is length - 1
		$is_list = true;
		for ($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
			if ($i != $keys[$i]) { //A key fails at position check.
				$is_list = false; //It is an associative array.
				break;
			}
		}
	}

	foreach ($arr as $key => $value) {
		if (is_array($value)) { //Custom handling for arrays
			if ($is_list)
				$parts[] = array2json($value); /* :RECURSION: */
			else
				$parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
		} else {
			$str = '';
			if (!$is_list)
				$str = '"' . $key . '":';

			//Custom handling for multiple data types
			if (is_numeric($value))
				$str .= $value; //Numbers
			elseif ($value === false) $str .= 'false'; //The booleans
			elseif ($value === true) $str .= 'true';
			else
				$str .= '"' . addslashes($value) . '"'; //All other things
			// :TODO: Is there any more datatype we should be in the lookout for? (Object?)

			$parts[] = $str;
		}
	}
	$json = implode(',', $parts);

	if ($is_list)
		return '[' . $json . ']'; //Return numerical JSON
	return '{' . $json . '}'; //Return associative JSON
}
// Function Section !!!
function addClient($data) {
	$query = "SELECT name FROM hosts WHERE name = \"" . $data['cname'] . "\"";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if ($num_rows > 0) {
		return "<label> Client Name : </label><span>" . $data['sname'] . "</span>\n<h4> Client [ <b> " . $data['sname'] . "</b> ] Already exist in the Database </h4>";
	}

	$insertClient = "INSERT INTO hosts (name,ip,active) VALUES (\"" . $data['cname'] . "\",\"" . $data['cip'] . "\",0)";
	mysql_query($insertClient) or die('Insert Client Host failed : ' . $insertClient . ' ' . mysql_error());

	return 100;
}
function addStorage($data) {

	$query = "SELECT id,name FROM Storage WHERE name = \"" . $data['sname'] . "\"";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		return "<label> Storage Name : </label><span>" . $data['sname'] . "</span>\n<h4> Storage [ <b> " . $data['sname'] . "</b> ] Already exist in the Database </h4>";
		exit (201);
	}

	$insertStorage = "INSERT INTO Storage (type,name,ip,active) VALUES (" . $data['stype'] . ",\"" . $data['sname'] . "\",\"" . $data['scip'] . "\",0)";
	mysql_query($insertStorage) or die('Insert Storage failed : ' . mysql_error());
	$id = mysql_insert_id();

	$insertNodeq = "INSERT INTO StorageNodes (clusterID,name,ip) VALUES ";

	for ($x = 1; $x <= 2; $x++) {
		$insertNode = $insertNodeq . "( " . $id . ",\"node" . $x . "\",\"" . $data['sn' . $x . 'ip'] . "\")";
		echo "SQL Insert " . $insertNode . "\n";
		mysql_query($insertNode) or die('Insert StorageNode node' . $x . ' failed : ' . $insertNode . ' ' . mysql_error());
	}
	return 200;
}
function addBackend($data) {
	$query = "SELECT id FROM StorageBackend WHERE name = '" . $data['bname'] . "'";
	$result = mysql_query($query) or die('Query failed: ' . mysql_error());
	$num_rows = mysql_num_rows($result);

	if ($num_rows > 0) {
		return "<label> Backend Storage Name : </label><span>" . $data['bname'] . "</span>\n<h4> Backend Storage [ <b> " . $data['sname'] . "</b> ] Already exist in the Database </h4>";
		exit (301);
	}

	$insertBackend = "INSERT INTO StorageBackend (name,type,ip,active) VALUES ( '" . $data['bname'] . "','" . $data['btype'] . "',\"" . $data['bip'] . "\",0)";
	echo $insertBackend . "\n";
	mysql_query($insertBackend) or die('Insert Storage Backend failed : ' . mysql_error());
	return 100;
}
function addTask($formData) {
	$date = date_create();

	$error = array ();
	foreach ($formData as $key => $value) {
		if (($value == '')) {
			array_push($error, $key);
		}
	}
	if ($error) {
		echo "ERROR : Missing values " . print_r($error) . "<br/>";
	} //exit(255);}

	$status = array ();
	$svcomm = array (
		'build' => "/bin/cat /compass/version",
		'vrmf' => "/bin/cat /compass/vrmf",
		'racemq' => "/data/race/rtc_racemqd -v"
	);

	$stgS = "SELECT active,ip FROM Storage WHERE id = \"" . $formData['addTask_Storage'] . "\"";     // Storage Check
	$stgB = "SELECT active FROM StorageBackend WHERE id = \"" . $formData['addTask_Backend'] . "\""; // Storage Backend Check
	$stgC = "SELECT name,active FROM hosts WHERE id = ";                                             // Stotage Clients Check

	$stgSr = mysql_query($stgS) or die('Unable To Query Storage Table' . $stgS . ' ERROR : ' . mysql_error()); //Storage Results Check
	$status['s'] = mysql_fetch_assoc($stgSr);

	$stgBr = mysql_query($stgB) or die('Unable To Query Backend Table ' . $stgB . ' ERROR : ' . mysql_error()); //Backend Results Check
	$status['b'] = mysql_fetch_assoc($stgBr);

	// Checking Clients Status
	foreach (explode(',', $formData['addTask_clients']) as $cliID) {
		$queryClients = $stgC . " " . $cliID . "";
		$stgCr = mysql_query($queryClients) or die('Unable To Query Backend Table ' . $stgB . ' ERROR : ' . mysql_error()); //Check Clients
		$stgCf = mysql_fetch_assoc($stgCr);
		if ($stgCf['active'] == 1) {
			$status['clients'] = 1;
		} else {
			$status['c'] = 0;
		}
	}

	if ($status['s']['active'] == 1 || $status['b']['active'] == 1 || $status['c']['active'] == 1) {
		echo "More then one parameter is being used ";
		exit (200);
	}

	$stgIP = $status['s']['ip'];
	//echo "Storage IP : ".$stgIP."<br>";
	if (!($connection = ssh2_connect($status['s']['ip'], 26))) {
		echo "Unable to Connect to " . $status['s']['ip'] . "<br>";
		echo "[FAILED]<br />";
		exit (251);
	}
	#echo "SSH Connection [OK] <br />";

	if (!(ssh2_auth_password($connection, 'root', 'l0destone'))) {
		echo "Unable to Connect to " . $stgIP . "<br>";
		echo "[FAILED]<br />";
		exit (252);
	}
	//echo "SSH Authentication [OK] <br />";
	foreach ($svcomm as $key => $value) {
		//echo "Key: $key; Value: $value<br />\n";
		$data = ssh2_exec($connection, $value);
		stream_set_blocking($data, true);
		$d = rtrim(stream_get_contents($data));
		//echo "Output: " .$d ;
		$formData[$key] = $d;
	}

	$race = preg_match("/race_mq v(.*?) \(/", $formData['racemq']);
	preg_match("/(\d.*)( \(.*)/", $formData['racemq'], $output); // extract race version
	$formData['racemq'] = $output[1];

    // Query RaceMQ on Table RaceMQBuilds
    //
    
	$dataKV['RaceMQBuildsID'] = getRaceMQID($formData[racemq]);
	$formData['timetamp'] = date_timestamp_get($date);

	//$dataKV['taskDate']       = date('l jS of F g:i A.', $formData[timetamp]);
	//$dataKV['taskDate']       = date('jS F Y h:i:s A (T)', $formData[timetamp]);
	$dataKV['taskDate'] = $formData[timetamp];
	$dataKV['storageName'] = $formData[addTask_Storage];
	$dataKV['storageSVC'] = $formData[vrmf] . " " . $formData[build];
	$dataKV['backendName'] = $formData[addTask_Backend];
	$dataKV['clientsName'] = $formData[addTask_clients];
	$dataKV['testUsed'] = $formData[addTask_TestType];
	$dataKV['userName'] = $formData[addTask_users];
	$dataKV['active'] = "0";

	$data = array ();
	$columns = array ();
	foreach ($dataKV as $key => $value) {
		$columns[] = $key;
		if ($value != "") {
			$data[] = "\"$value\"";
		} else {
			$data[] = "NULL";
		}
	}
	$cols = implode(",", $columns);
	$values = implode(",", $data);

	$insertTaskQ = "INSERT INTO `runningTask` (" . $cols . ") VALUES (" . $values . ")";
	mysql_query($insertTaskQ) or die("Task Insert <b>ERROR</b> : Unable to Insert Query <br />" . $insertTaskQ . "<br />" . mysql_error());
	//echo "<br>Results Status : " .print_r($formData)."<br>RACE:" .print_r($race)."<br>MYSQL INSERT :" .$insertQuery."<br />";

	$stgUpdate = "UPDATE automation.Storage SET active = '1' WHERE Storage.id = \"" . $dataKV['storageName'] . "\"";
	mysql_query($stgUpdate) or die("<b>ERROR</b> Update Storage active : <br />" . $stgUpdate . "<br />" . mysql_error());

	if ($dataKV['backendName'] != "1") {
		$bakUpdate = "UPDATE `automation`.`StorageBackend` SET `active` = '1' WHERE `StorageBackend`.`id` = \"" . $dataKV['backendName'] . "\"";
		mysql_query($bakUpdate) or die("<b>ERROR</b> Update backend active : <br />" . $bakUpdate . "<br />" . mysql_error());
	}

	foreach (explode(",", $dataKV['clientsName']) as $cliID) {
		$cliUpdate = "UPDATE `automation`.`hosts` SET `active` = '1' WHERE `hosts`.`id` = \"" . $cliID . "\"";
		mysql_query($cliUpdate) or die("<b>ERROR</b> Udpate clients ID : " . $cliID . " <br />" . $cliUpdate . "<br />" . mysql_error());
	}

}
function getRaceMQID($race){ 

    $stgR = "SELECT id FROM RaceMQBuilds WHERE build = \"" . $race . "\""; // SELECT RaceMQ Build ID from Table 
    $result = mysql_query($stgR) or die ("<label>ERROR: Unable to Query RaceMQ from table RaceMQBuilds</label><br>".mysql_error());
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
        $raceID = mysql_fetch_assoc($result);
		return  $raceID["id"];
	}

    $insertRace = " INSERT INTO RaceMQBuilds ( race ) VALUES ( " .$race.") ";
	mysql_query($insertRace) or die('Insert RaceMQ failed : ' . $insertRace . ' ' . mysql_error());
	$id = mysql_insert_id();

    return $id;

}
function removeTask($taskID) {
	$active = "1";

	$queryTaskIDs = "SELECT `storageName`, `backendName`, `clientsName`, `active` FROM `runningTask` WHERE id = \"" . $taskID . "\"";
	$selResultsTask = mysql_query($queryTaskIDs) or die('Unable To Query Storage Table ERROR : ' . mysql_error()); //Storage Results Check

	$data = mysql_fetch_assoc($selResultsTask);

	echo "This is the tasks " . print_r($data) . "\n";

	$taskUpdate = "UPDATE automation.runningTask SET active = '$active' WHERE runningTask.id = \"" . $taskID . "\"";
	echo "TASK UPDATE " . $taskUpdate . "\n";
	mysql_query($taskUpdate);
	if (mysql_error()) {
		return "301";
		exit (301);
	};

	$active = "0";
	$stgUpdate = "UPDATE automation.Storage SET active = '$active' WHERE Storage.id = \"" . $data['storageName'] . "\"";
	mysql_query($stgUpdate);
	if (mysql_error()) {
		return "301";
		exit (301);
	};
	echo "Storage UPDATE " . $stgUpdate . "\n";
	if ($data['backendName'] != "1") {
		$bakUpdate = "UPDATE automation.StorageBackend SET active = '$active' WHERE StorageBackend.id = \"" . $data['backendName'] . "\"";
		mysql_query($bakUpdate);
		if (mysql_error()) {
			return "301";
			exit (301);
		};
		echo "Backend UPDATE " . $bakUpdate . "\n";
	}
	foreach (explode(',', $data['clientsName']) as $cliID) {
		$cliUpdate = "UPDATE automation.hosts SET active = '$active' WHERE hosts.id = \"" . $cliID . "\"";
		mysql_query($cliUpdate);
		if (mysql_error()) {
			return "301";
			exit (301);
		};
		echo "CLIENT UPDATE " . $cliUpdate . "\n";
	}
	return "300";

}
function getNodesIPs($stgName) {
  $query = "SELECT DISTINCT StorageNodes.ip , StorageNodes.name
                          FROM automation.Storage Storage
                    INNER JOIN automation.StorageNodes StorageNodes
                            ON (Storage.id = StorageNodes.clusterID)
                         WHERE (Storage.name = '$stgName')";
  
  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  $nodes = array();
  while ( $r = mysql_fetch_array($result)) {
    $nodes[$r['name']] = $r['ip'];
  }
  return $nodes;
}

function getStorageInfo($stg) {
	$svcomm = array (
		'build' => "/bin/cat /compass/version",
		'vrmf' => "/bin/cat /compass/vrmf",
		'racemq' => "/data/race/rtc_racemqd -v"
	);
        $stgType = selStorageByType($stg['name']);

	if (!($connection = ssh2_connect($stg['name'], 26))) {

	  echo "Unable to Connect to Cluster Node" . $stg['name'] . "<br>";
          $stgNodes = getNodesIPs($stg['name']);

                foreach ($ip as $key => $value ) {
	          if (!($connection = ssh2_connect($stg['name'], 26))) {
	            print "<label style=\"color:white;\">RaceMQ Name <b style=\"color:red;\">".$ip." : ".$kay. "</b></label><br>";
                  }
                }
		echo "[FAILED]<br />";
                $formData['id'] = $stg['id'];
                $formData['name'] = $stg['name'];
                $formData['Type'] = $stgType;
                $formData['build']= "Unable to retrive SVC Build";
                $formData['racemq']= "Unable to tertive RaceMQ Build ";
		#return (251);
		return $formData;
	}

	if (!(ssh2_auth_password($connection, 'root', 'l0destone'))) {
		echo "User/Password are in Correct while connectiong to : [ " . $stg['name']. " ]<br>";
		echo "[FAILED]<br />";
		exit (252);
	}
	//echo "SSH Authentication [OK] <br />";
        $formData['id'] = $stg['id'];
        $formData['name'] = $stg['name'];
        $formData['Type'] = $stgType;
        #$log->LogInfo("Storage Name : .$stg['name']");
	foreach ($svcomm as $key => $value) {
		//echo "Key: $key; Value: $value<br />\n";
		$data = ssh2_exec($connection, $value);
		stream_set_blocking($data, true);
		$d = rtrim(stream_get_contents($data));

                if ( $key === "racemq" ) {
                 $matches = null; 
                  $pattern = '/race_mq (.*) \(/';
	          #print "<label style=\"color:white;\">RaceMQ Name <b style=\"color:red;\">".$key." : ".$d. "</b></label><br>";

                  $dres = preg_match($pattern,$d,$matches);
                  if (empty($matches[1])){
                    $d = "RaceMQ Ver Unavil";
                  }
                  else {
                  #print "<label style=\"color:white;\">RaceMQ Name da <b style=\"color:red;\">".$matches[1]. "</b></label><br>";
                  #logFileWrite("INFO","RaceMQ Version".$dres[1]);
                    $d = $matches[1];
                  }
                }
                $d = trim($d);
                if (empty($d)){
                  $d = "Unable to retrive ";
                }
		//echo "Output: " .$d ;
		$formData[$key] = $d;
		//$formData[$stgName][$key] = $d;
	}
	return $formData;
}
?>
