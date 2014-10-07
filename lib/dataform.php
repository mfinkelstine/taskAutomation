<?php
include "dbconnect.php";
include "function.php";

$parts = explode('&', $_SERVER["QUERY_STRING"]);
#$parts = explode('&', $_SERVER["QUERY_STRING"][1]);
#echo "Data from web" . print_r($parts)."\n";
foreach ($parts as $part) {
  #echo "foreach Data " . print_r($part)."\n";
  $part = explode('=',$part);
  $formTypeParam[$part[0]] = $part[1];
}
#echo "foreach Data " . print_r($formTypeParam)."\n";
// Insert Task to the System section
if ( $formTypeParam['type'] == "addTask" ){
  echo "FormType : ". $formTypeParam['type'] ."<br>".print_r($formTypeParam)."<br>";
  addTask($formTypeParam);
}

// Adding Storage
else if ( $formTypeParam['type'] == "addStorage" ){
  addStorage($formTypeParam);
  echo $value;
}

// Adding Clients to Database
else if ( $formTypeParam['type'] == "addClient" ) {
  //echo "Hello This is the time that I got : ".$formTypeParam['type'] ."\n";
  $value = addClient($formTypeParam);
  #echo "Hello This is the time that I got ". $formTypeParam['type'] ." and this value  ".$value ."\n";
  echo $value;
}
// Adding Backend Storage
else if ( $formTypeParam['type'] == "addBackend" ) {
  $status = addBackend($formTypeParam);
  #echo "Hello This is the time that I got : ".$formTypeParam['type'] ."\n";
  echo $status;
}
?>
