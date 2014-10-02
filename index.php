<?php
include_once 'lib/dbconnect.php';
include_once 'lib/function.php';

$selClients = selActive("hosts"); # Select Active Clients from  : hosts
$selStorage = selActive("Storage"); # Select Active Storage  from : Storage
$selTask = selActiveTask(); # Select Active Task from     : runningTask
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Test Page</title>
    <script src="js/jquery-2.1.1.min.js"></script>
    <link rel='stylesheet' href='css/working/body.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/working/forms.css' type='text/css' media='all' />
	<script src="js/jquery.bpopup.min.js"></script>
	<script src="js/running_tasks.js" type="text/javascript"></script>
	<script src="js/jquery-ui.min.js" type="text/javascript"></script>
	<link rel='stylesheet' href='js/jquery-ui.min.css' type='text/css' media='all' />
	<script>
    	var clients 	= <?=$selClients?>;
        var storage 	= <?=$selStorage?>;
        var task_list 	= <?=$selTask?>;

          $(document).ready(function(){
          	var rowColor = "table_dataBGa";

          	if ( task_list == "") {
            	$('#task_list').append('<tr  class="table_dataBGa"> <td colspan="11">No Data Found </td></tr>');
            } else {
            	$.each(task_list, function(i,task) {
            	var cli_name;

              	if ( task.stat === "0" ) {
                	var stats = "RUNNING";
              	}
              		$('#task_list').append('<tr class="'+rowColor+'" id="taskID_'+task.id+'" value="'+task.id+'"><td class="minusTd button" id="removeTask" value="'+task.id+'"></td><td>'+task.taskDate+'</td><td>'+task.storage_name+'</td><td>'+task.storageSVC+'</td><td>'+task.storageRaceMQ+'</td><td>'+task.backend_name+'</td><td>'+task.clientsName+'</td><td>'+task.test_type+'</td><td>'+stats+'</td><td>'+task.user_name+'</td><td class="plusTd button" id="addingTask" value="'+task.id+'"></td></tr>');
            		if ( rowColor == "table_dataBGa") { rowColor ="table_dataBGb" ;} else {rowColor ="table_dataBGa" ;}
            	});
            }
            if ( clients == "") {
            	$('#clients_list').append('<tr  class="table_dataBGa"> <td colspan="3" style="color:red>No Data Found </td></tr>');
            } else {

            	$.each(clients, function(i, client) {
              		$('#clients_list').append('<tr class="'+rowColor+'"><td>'+client.id+' </td> <td> '+client.name+' </td> <td>IDLE</td></tr>');
              		if ( rowColor == "table_dataBGa") { rowColor ="table_dataBGb" ;} else {rowColor ="table_dataBGa" ;}
            	});
            }
            if ( storage == "") {
            	$('#storage_list').append('<tr  class="table_dataBGa"> <td colspan="6">No Data Found </td></tr>');
            } else {
            	$.each(storage, function(i, stg) {
              		$('#storage_list').append('<tr class="'+rowColor+'" ><td value="'+stg.id+'">'+stg.name+' </td> <td> '+stg.vrmf+' ('+stg.build+') </td><td>'+stg.racemq+'</td> <td>Free</td></tr>');
              		if ( rowColor == "table_dataBGa") { rowColor ="table_dataBGb" ;} else {rowColor ="table_dataBGa" ;}
            	});
            }
          });
 	</script>
</head>
<body>

<p style="color: red;">Two tables, side by side, centered together within the page. But if the page is not wide enough,
the second table will move on to the next line, and will be centered there instead.
</p>

<div class="outerdiv">
	<div>
			<table id="task_list" class="task_table">
				<tr class="table_titaleBG" id="">
					<th class="table_title table_ffs" colspan="10" >Tasks List</th>
					<th class="plusTd button" id="addTask"></th>
				</tr>
				<tr class="table_header">
					<th>ID</th>
					<th>DATE ISSUED</th>
					<th>Storage Name</th>
					<th>SVC Version/Build</th>
					<th>Race Build</th>
					<th>Backend</th>
					<th>Clients</th>
					<th>Test Running</th>
					<th>Task Status</th>
					<th colspan="2" >Used By</th>
				</tr>
			</table>

	</div>
	<div>
		<!-- CLIENTS TABLE -->
			<table id="clients_list" class="clients_table">
				<tr class="table_titaleBG" id="">
					<th class="table_title table_ffs" colspan="2" >Host List</th>
					<th class="plusTd button" id="addClient"></th>
				</tr>
				<tr class="table_header">
					<th>ID</th>
					<th>Client Name</th>
					<th>Status</th>

				</tr>
				<tr class="table_dataBGa">
					<td>X1</td>
					<td>MC001</td>
					<td>IDLE</td>
				</tr>
			</table>
			<!-- STORAGE TABLE -->
			<table id="storage_list" class="storage_table">
				<tr class="table_titaleBG">
					<th class="table_title table_ffs" colspan="3" >Storage List</th>
					<th class="plusTd button" id="addStorage"></th>
				</tr>
				<tr class="table_header">
					<!-- <th>ID</th> -->
					<th>Storage Name</th>
					<th>SVC Version/Build</th>
					<th>RaceMQ Build</th>
					<th>Status</th>

				</tr>
				<tr class="table_dataBGa">
                                        <!-- <td>2</td> --> 
                                        <td>rtc02f ( FAB1 )</td><td>7.4.0.0 (build 101.001.1010101)</td><td>2.3.4.1</td><td>Free</td>
				</tr>
				<tr class="table_dataBGb">
					<!-- <td>2</td> -->
					<td>rtc02f</td><td>7.4.0.0 (build 101.001.1010101)</td><td>2.3.4.1</td><td>Free</td>
				</tr>

			</table>

	</div>
</div>

<!-- FORM SECTION -->
<!-- Add Task Form -->
  <div class="addTask_form" id="addTask_form">
<!-- <div class="addTask_form" id="addTask_form" >-->
   <!-- <form id="addTask_form_data"> -->
   	<div>
    <!-- <form id="addTask_form" class="addTask_form_data">  -->
    	<span>Select Storage Task</span>
      	<select id="task_storage_select">
       		<option value="none" selected>--- please select ---</option>
       		<option value="0">Single Task</option>
       		<option value="1">Group Task</option>
    	</select>
      </div>
	<!-- Single Storage Task -->
    <form id="single_storage" >
    	<table>
      		<tr><td>Storage Name</td><td>    <select name="addTask_Storage"  id="addTask_Storage" ></select></td></tr>
      		<tr><td>Storage Backend</td><td> <select name="addTask_Backend"  id="addTask_Backend" ></select></td></tr>
      		<tr><td>Clients List</td><td>    <select name="addTask_clients" id="addTask_clients" multiple="multiple" ></select></td></tr>
      		<tr><td>Test Type</td><td>       <select name="addTask_TestType" id="addTask_TestType"> </select></td></tr>
      		<tr><td>User Name</td><td>       <select name="addTask_users"    id="addTask_users">       </select></td></tr>
     	</table>
     </form>
     <!-- Group Storage Task -->
	 <form id="group_storage">
	 	<table>
			<tr><td>Storage Name</td><td>    <select name="addgrpTask_Storage"  id="addgrpTask_Storage" multiple="multiple"></select></td></tr>
			<tr><td>Backend Storage</td><td> <select name="addgrpTask_Backend" id="addgrpTask_Backend"></select></td></tr>
			<tr><td>Client Name</td><td>    <select name="addgrpTask_clients"  id="addgrpTask_clients" multiple="multiple"></select></td></tr>
		</table>
     </form>
  </div>

<!-- Add Client Form -->
  <div class="addClient_form" id="addClient_form">
    <form id="addClient_form_data">
      <label>Add Client to Database</label><br><br>
      <table>
        	<tr><td>Server Name</td><td>		<input name="cname" type="text" placeholder="Server Name" /></td></tr>
        	<tr><td>Server IP</td><td>			<input name="cip"   id="sip" type="text" /></td></tr>
        </table>
    </form>
  </div>

<!-- Add Storage Form -->
  <div class="addStorage_form" id="addStorage_form">
    <div><span>Add Storage or Backend </span>
      <select id="storage_backend">
        <option value="none" selected>--- please select ---</option>
        <option value="storage">Add Storage</option>
        <option value="backend">Add Backend</option>
      </select>
    </div>
    <!-- Storage Form -->
    <form id="addStorage_form_data">
      <label>Insert Storage to Database</label><br><br>
        <table>
        	<tr><td>Storage Name</td><td>		<input name="sname" type="text" class="m-wrap" /></td></tr>
        	<tr><td>Storage Cluster IP</td><td>	<input name="scip"  type="text" class="m-wrap"/></td></tr>
        	<tr><td>Storage Node1 IP</td><td>  	<input name="sn1ip" type="text" class="m-wrap"/></td></tr>
        	<tr><td>Storage Node2 IP</td><td>   <input name="sn2ip" type="text" class="m-wrap"/></td></tr>
        	<tr><td>Storage Type</td><td>		<select name="stype" id="stype"></select></td></tr>
        </table>
    </form>
	<!-- Backend Form -->
    <form id="addBackend_form_data">
      <label>Insert Backend to Database</label><br><br>
      	<table>
        <tr><td>Backend Name</td><td><input name="bname" type="text" class="m-wrap"></td></tr>
        <tr><td>Backend IP</td><td><input name="bip"  type="text" class="m-wrap"></td></tr>
        <tr><td>Backend Type</td><td><select name="btype" id="btype" ></select></td></tr>
        </table>
    </form>
  </div>

<!--DIALOG -->
<div id="dialog-confirm" >
</div>
<div id="addTestToTask" >
	<div><span>Select Test To ADD</span>
      <select id="task_name">
        <option value="none" selected>--- select Test---</option>
        <option value="csop">CSOP</option>
        <option value="vdbench">VDBENCH</option>
        <option value="tpcc">TPCC</option>
      </select>
    </div>
</div>
<div id="taskDisplay">
<label>Active Task List</label>
	<table>
		<tr><th>Task Name<th></tr>
	</table>
<label>Active Task List</label>
</div>


</body>
</html>
