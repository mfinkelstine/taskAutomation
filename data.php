<?php 
include 'lib/dbconnect.php';
include 'lib/function.php';

$datatype = $_GET['data'];
$type = strtolower($datatype);

#print "<h1> " .print_r($type)."</h1>";

if ( $type == "addtask"){ 
  #$selClients   = "SELECT id,name FROM hosts WHERE active   = 0";
  $selClients   = selActive("hosts"); # Clients hosts table : hosts
#  $selStorage   = "SELECT id,name FROM storage WHERE active = 0";
  $selStorage   = selActive("Storage"); # Storage Table : Storage
#  $selBackend   = "SELECT id,name FROM StorageBackend WHERE active = 0";
  $selBackend   = selActive("StorageBackend"); #Storage Backend Table : StorageBackend
#  $selTestmode  = "SELECT id,name FROM testmode";
  $selTestType  = selTestType();
  $selUsers     = selUserList();


} elseif ( $type == "addstorage" ) {
  print "<h1> You have selected ". $datatype ." </h1>";

} elseif ( $type == "addclient" ) {
  print "<h1> You have selected ". $datatype ." </h1>";

}




?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 
   'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
  <head>
    <script src="js/jquery-2.1.1.min.js"></script>
    <script>
      var activeClients = <?=$selClients ?>;
      var activeStorage = <?=$selStorage ?>;
      var activeBackend = <?=$selBackend ?>;
      var testType      = <?=$selTestType ?>;
      var userList      = <?=$selUsers ?>;

  
       $(document).ready(function() {
        $.each(activeStorage, function(i, stg) {
          $('#storage_list').append($('<option>', {
            value: stg.id,
            text:  stg.name
          
          }));   
        });
        $.each(activeBackend, function(i, back) {
          $('#backend_list').append($('<option>', {
            value: back.id,
            text:  back.name 
          }));
        });

        $.each(activeClients, function(i, client) {
          $('#clients_list').append($('<option>', {
            value: client.id,
            text:  client.name 
          }));
        });
        $.each(testType, function(i, tt) {
          $('#testtype_list').append($('<option>', {
            value: tt.id,
            text:  tt.name 
          }));
        });
      });
    </script>
  </head>
  <body>

  <h1> You have selected <?=$datatype ?></h1>
  <form id="<?=$datatype ?>" >
    <?php if ($type == "addtask") { ?> 
      <div>
       <div style='width: 150px; display: inline-block; border: 0px solid black'>Select Storage</div>
          <select id="storage_list">
          </select><br>
       <div style='width: 150px; display: inline-block; border: 0px solid black'>Select Backend</div>
          <select id="backend_list">
          </select><br>
       <div style='width: 150px; display: inline-block; border: 0px solid black'>Select Clients</div>
          <select id="clients_list" multiple="multiple">
          </select><br>
        <div style='width: 150px; display: inline-block; border: 0px solid black'>Select Test Type</div>
          <select id="testtype_list">
          </select><br>
        
      </div>
      <input type="submit" value="addTaskSubmit">


    <?php } ?>
    <?php if ($type == "addstorage") { ?> 
      <div>
        <div style='width: 150px; display: inline-block; border: 0px solid black'>Storage Name</div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
        <div style='width: 150px; display: inline-block; border: 0px solid black'>Storage Cluster IP</div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
        <div style='width: 150px; display: inline-block; border: 0px solid black'>Storage Node1 IP</div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
        <div style='width: 150px; display: inline-block; border: 0px solid black'>Storage Node2 IP</div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
        <div style='width: 150px; display: inline-block; border: 0px solid black'>Storage Type </div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
      </div>
    <?php } ?>

      <?php if ($type == "addclient") { ?> 
        <div> 
          <div style='width: 150px; display: inline-block; border: 0px solid black'>Client Name</div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
          <div style='width: 150px; display: inline-block; border: 0px solid black'>Client IP </div><input id="vdiskCount" name="vdiskCount" type="text" /><br/>
        </div>
      <?php } ?>

    </form>  
  </body>
</html>
