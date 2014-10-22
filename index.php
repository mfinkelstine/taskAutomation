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
<title>Automation Page</title>
    <script src="js/jquery-2.1.1.min.js"></script>
    <link rel='stylesheet' href='css/working/body.css' type='text/css' media='all' />
	<link rel='stylesheet' href='css/working/forms.css' type='text/css' media='all' />
	<script src="js/jquery.bpopup.min.js"></script>
	<script src="js/running_tasks.js" type="text/javascript"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>

        <!-- Simple Module JS -->
        <!-- 
          <script type='text/javascript' src='js/sm/jquery.js'></script>
        -->
        <script type='text/javascript' src='js/sm/basic.js'></script> 
        <script type='text/javascript' src='js/sm/jquery.simplemodal.js'></script>
        <!-- Simple Module CSS 
          <link type='text/css' href='css/css_sm/demo.css' rel='stylesheet' media='screen' />-->
        <!-- Contact Form CSS files -->
          <link type='text/css' href='css/css_sm/basic.css' rel='stylesheet' media='screen' />

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
                var ntaskList;

              	if ( task.stat === "0" ) {
                	var stats = "RUNNING";
              	}
                var newtaskList; 
                ntaskList = '<tr class="'+rowColor+'" id="taskID_'+task.id+'" value="'+task.id+'">';
                ntaskList += '<td class="minusTd button" id="removeTask" value="'+task.id+'"></td>';
                ntaskList += '<td id="showTaskList">'+task.taskDate+'</td><td>'+task.storage_name+' ( ' +task.type+' ) </td>';
                ntaskList += '<td>'+task.storageSVC+'</td><td>v'+task.RaceBuild+'</td><td>'+task.backendName+'</td>';
                ntaskList += '<td>'+task.clientsName+'</td><td>'+task.testUsed+'</td>';
                ntaskList += '<td>'+stats+'</td>';
                ntaskList += '<td>'+task.user_name+'</td>';
                ntaskList += '<td class="plusTd button" id="addingTask" value="'+task.id+'"></td></tr>';
                console.debug("STRING " + ntaskList);
                        //$('#task_list').append('<tr class="'+rowColor+'" id="taskID_'+task.id+'" value="'+task.id+'"><td class="minusTd button" id="removeTask" value="'+task.id+'"></td><td>'+task.taskDate+'</td><td>'+task.storage_name+' ( ' +task.type+' ) </td><td>'+task.storageSVC+'</td><td>'+task.storageRaceMQ+'</td><td>'+task.backend_name+'</td><td>'+task.clientsName+'</td><td>'+task.test_type+'</td><td>'+stats+'</td><td>'+task.user_name+'</td><td class="plusTd button" id="addingTask" value="'+task.id+'"></td></tr>');
                        $('#task_list').append(ntaskList);
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
              		$('#storage_list').append('<tr class="'+rowColor+'" ><td value="'+stg.id+'">'+stg.name+' ( '+stg.Type+ ' ) </td> <td> '+stg.vrmf+' ('+stg.build+') </td><td>'+stg.racemq+'</td> <td>Free</td></tr>');
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
				<!-- <tr class="table_dataBGa">
                                        <td>rtc02f ( FAB1 )</td><td>7.4.0.0 (build 101.001.1010101)</td><td>2.3.4.1</td><td>Free</td>
				</tr>
				<tr class="table_dataBGb">
					<td>rtc02f</td><td>7.4.0.0 (build 101.001.1010101)</td><td>2.3.4.1</td><td>Free</td>
				</tr> -->

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
    <br>
	<!-- Single Storage Task -->
    <form id="single_storage" >
    	<table>
      		<tr><td>Storage Name</td><td>    <select name="addTask_Storage"  id="addTask_Storage" ></select></td></tr>
      		<tr><td>Storage Backend</td><td> <select name="addTask_Backend"  id="addTask_Backend" ></select></td></tr>
      		<tr><td>Clients List</td><td>    <select name="addTask_clients" id="addTask_clients" multiple="multiple" ></select></td></tr>
      		<tr><td>Test Type</td><td>       <select name="addTask_TestType" id="addTask_TestType"> </select></td></tr>
      		<tr><td>User Name</td><td>       <select name="addTask_users"    id="addTask_users">       </select></td></tr>
     	</table>
<button type="button" class="button positive save">
    <img src="/images/tick.png" alt="Save"> Save
</button>
     </form>
     <!-- Group Storage Task -->
	 <form id="group_storage">
	 	<table>
			<tr><td>Storage Name</td><td>    <select name="addgrpTask_Storage"  id="addgrpTask_Storage" multiple="multiple"></select></td></tr>
			<tr><td>Backend Storage</td><td> <select name="addgrpTask_Backend" id="addgrpTask_Backend"></select></td></tr>
			<tr><td>Client Name</td><td>    <select name="addgrpTask_clients"  id="addgrpTask_clients" multiple="multiple"></select></td></tr>
		</table>
        <button type="button" class="button positive save">
            <img src="/images/tick.png" alt="Save"> Save
        </button>
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
<div id="displayTaskLists">
<label>Running Tasks</label>
	<table>
		<tr><th>Task Name<th></tr>
	</table>
<label>Tasks History</label>
  <div id='logo'>
    <h1>Simple<span>Modal</span></h1>
    <span class='title'>A Modal Dialog Framework Plugin for jQuery</span>
  </div>
  <div id='content'>
    <div id='basic-modal'>
      <h3>Basic Modal Dialog</h3>
      <p>A basic modal dialog with minimal styling and no additional options. There are a few CSS properties set internally by SimpleModal, however, SimpleModal relies mostly on style options and/or external CSS for the look and feel.</p>
      <input type='button' name='basic' value='Demo' class='basic'/> or <a href='#' class='basic'>Demo</a>
    </div>
    
    <!-- modal content -->
    <div id="basic-modal-content">
      <h3>Basic Modal Dialog</h3>
      <p>For this demo, SimpleModal is using this "hidden" data for its content. You can also populate the modal dialog with an AJAX response, standard HTML or DOM element(s).</p>
      <p>Examples:</p>
      <p><code>$('#basicModalContent').modal(); // jQuery object - this demo</code></p>
      <p><code>$.modal(document.getElementById('basicModalContent')); // DOM</code></p>
      <p><code>$.modal('&lt;p&gt;&lt;b&gt;HTML&lt;/b&gt; elements&lt;/p&gt;'); // HTML</code></p>
      <p><code>$('&lt;div&gt;&lt;/div&gt;').load('page.html').modal(); // AJAX</code></p>
    
      <p><a href='http://www.ericmmartin.com/projects/simplemodal/'>More details...</a></p>
    </div>

    <!-- preload the images -->
    <div style='display:none'>
      <img src='img/basic/x.png' alt='' />
    </div>
</div>


</body>
</html>
