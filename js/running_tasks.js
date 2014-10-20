function sumbitForm(formdata,formName) {
	$.ajax({
		type : "GET",
		url : 'lib/dataform.php',
		data : formdata
	}).done(function(data) {
		successData(data);
		console.log("Data Results" + data + "\n");
	});
}

function timeout_init(time) {
	setTimeout(function() {
		window.location.reload();
                document.getElementById( "form" ).reset();
	}, time);
}
function taskSelect(id,taskType) {
	if ( id === "0" ) {
		console.debug("VALUE A single "+id);
		$("#group_storage").hide();
		$("#single_storage").show();

		$.ajax({
			type : "GET",
			dataType : "json",
			url : 'lib/data_new.php',
			cache: false,
			data : 'data=' + taskType,
			success : function(data) {
				console.debug(data);
				$.each(data,function(index,d) {
					if (index == "hosts") {
						console.log("hosts "+ index+ " : ");
						$('#addTask_clients').append('<option value="none" selected >NONE</option>');
						$.each(data['hosts'],function(i,value) {
							$('#addTask_clients').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					} else if (index == 'storage') {
						$('#addTask_Storage').append('<option value="none" selected >NONE</option>');
						$.each(data['storage'],function(i,value) {
							$('#addTask_Storage').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					}
					else if (index == 'backend') {
						$('#addTask_Backend').append('<option value="none" selected >NONE</option>');
						$.each(data['backend'],function(i,value) {
							$('#addTask_Backend').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					}
					else if (index == 'testtype') {
						$('#addTask_TestType').append('<option value="none" selected >NONE</option>');
						$.each(data['testtype'],function(i,value) {
							$('#addTask_TestType').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					} else if (index == 'users') {
						$('#addTask_users').append('<option value="none" selected >NONE</option>');
						$.each(data['users'],function(i,value) {
							$('#addTask_users').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					}
				});
			},
			error : function(jError) {
				alert("Error Data : "+ jError);
			}
		});
	} else if ( id === "1") {
		console.debug("Group Task" +id);
		$("#single_storage").hide();
		$("#group_storage").show();

		$.ajax({
			type : "GET",
			dataType : "json",
			url : 'lib/data_new.php',
			cache: false,
			data : 'data=' + taskType,
			success : function(data) {
				console.debug(data);
				$.each(data,function(index,d) { 
					if (index == "hosts") {
						console.log("hosts "+ index+ " : ");
						$('#addTask_clients').append('<option value="none" selected >NONE</option>');
						$.each(data['hosts'],function(i,value) {
							$('#addgrpTask_clients').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});

					} else if (index == 'storage') {
						$('#addTask_Storage').append('<option value="none" selected >NONE</option>');
						$.each(data['storage'],function(i,value) {
							$('#addgrpTask_Storage').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					}
					else if (index == 'backend') {
						$('#addTask_Backend').append('<option value="none" selected >NONE</option>');
						$.each(data['backend'],function(i,value) {
							$('#addgrpTask_Backend').append('<option value="'+ value.id+ '">'+ value.name+ '</option>');
						});
					}
				});
			},
			error : function(jError) {
				alert("[ERROR] Group Task Data : "+ jError);
			}
		});
	}
}

function successData(data) {
	$('#dataResults').bPopup();
	$('#dataResults').html(data);
	$('#dataResults b').css("color", "blue");
}
// jquery Start HERE....
$(document).ready(function() {
	var resizeText = function() {
		// Standard height, for which the body font size is
		// correct
		var preferredFontSize = 70; // %
		var preferredSize = 1024 * 768;
		var currentSize = $(window).width() * $(window).height();
		var scalePercentage = Math.sqrt(currentSize)/ Math.sqrt(preferredSize);
		var newFontSize = preferredFontSize * scalePercentage;
		$("body").css("font-size", newFontSize + '%');
	};

	$(window).bind('resize', function() {
		resizeText();
	}).trigger('resize');
	var dialog, form;
    $("#displayTaskLists").hide();
	$("#dataResults").hide();
	$("#addTask_form").hide();		// Adding Task to Database;
	$("#addClient_form").hide();	// Adding Client To database;
	$("#addStorage_form").hide();	// Adding Storage to database
	$("#addTestToTask").hide();     // Adding test to exist task hide it from being visible 

	$(".button").click(function() {
		var formID = this.id;
		// Clear selection from form
		//$("#addStorage_form_data").empty();
		//$("#addBackend_form_data").empty();
		
		console.debug("VAL ID " + formID);
		if (formID == "addClient") { //addClient_form ***************************************************************************
			// $("#sip").mask("9?99.9?99.9?99.9?99",
			// {placeholder:" "});
			console.log("#" + formID + "_form");
			$("#addClient_form").dialog({
				resizable : false,
				height : 'auto',width : 'auto',
				title : "Adding Client",
				modal : true,
				buttons : {
					"Submit" : function() {
						//***************************************************************************
						//var clientSerialize = $('#addClient_form_data').serialize();
						console.debug("adding client to database : "+$('#addClient_form_data').serialize());
						sumbitForm("type=addClient&" + $('#addClient_form_data').serialize());
						window.setTimeout('location.reload()', 8000);
						$('#addClient_form').trigger("reset");
						return false;
					}
				},
				Cancel : function() {
					$(this).dialog("close");
				}
			});
		}
		if (formID == "addStorage") { //addStorageBackend_form ***************************************************************************
			var storage_type ;
			$("#addStorage_form").dialog({
				resizable : false,
				height : 'auto',width : 'auto',
				title : "Add Storage/Backend ",
				modal : true,
				buttons : {
					"Submit" : function(type) {
						if (storage_type === "storage"){
							//console.debug("type storage : "+storage_type+"\nserialize : "+$('#addStorage_form_data').serialize());
							var storage_serialize = $('#addStorage_form_data').serialize();
							sumbitForm("type=addStorage&"+ storage_serialize);
							// console.log("Return Data from submitForm : "+ value + "\n");
							// window.setTimeout('location.reload()', 8000);
							console.debug("\nAdding New Storage : Data :" +backend_serialize+"\n");
							$('#addStorage_form').trigger("reset");
							location.reload();
							return false;
						} else if ( storage_type === "backend") {
							//console.debug("type backend : "+storage_type+"\nserialize : "+$('#addBackend_form_data').serialize());
							var backend_serialize = $('#addBackend_form_data').serialize();
							sumbitForm("type=addBackend&" + backend_serialize);
							console.debug("\nAdding New Backend Storage : Data :" +backend_serialize+"\n");
							// window.setTimeout('location.reload()', 8000);
							$('#addBackend_form_data').trigger("reset");
							location.reload();
							return false;
						}
					}
				},
				Cancel : function() {
					$(this).dialog("close");
				}
			});
			
			$('#addStorage_form_data').hide();
			$('#addBackend_form_data').hide();
			$("#storage_backend").change(function() {
				if (this.value === "storage") {
					storage_type = this.value;
					$('#addBackend_form_data').hide();
					$('#addStorage_form_data').show();
					$('#stype').empty();
					//$("select option").prop("selected", false);
					$.ajax({
						type : "GET",
						dataType : "json",
						url : 'lib/data_new.php',
						data : 'data='+ formID,
						success : function(data) {
							console.debug(data);
							$('#stype').append('<option value="none" selected>NONE</option>');
							$.each(data,function(i,value) {
								$('#stype').append('<option value="'+ value.id+ '">'+ value.type+ '</option>');
							});
						},
						error : function(data) {
							console.debug(data);
							$('#stype').append('<option selected>ERROR NO DATA </option>');
						}
					});
					return this.value;
				} else if (this.value === "backend") {
					storage_type = this.value;
					//$("select option").prop("selected", false);
					$("#btype").empty();
					
					$('#addStorage_form_data').hide();
					$('#addBackend_form_data').show();
					$.ajax({
						type : 'GET',
						dataType : 'json',
						url : 'lib/data_new.php',
						data : 'data=add'+ this.value,
						success : function(data) {
							$('#btype').append('<option value="none" selected>NONE</option>');
							$.each(data,function(i,value) {
								$('#btype').append('<option value="'+ value.id+ '">'+ value.type+ '</option>');
							});

						},
						error : function(data) {
							console.debug("WHERE "+data);
							$('#btype').append('<option selected>ERROR NO DATA </option>');
						}
					});
				}
			});
		}
		//**************************************** ADD TASK ****************************************
		if (formID == "addTask") {
			var tskval;
			$('#task_storage_select selected').trigger("reset");

			$("#single_storage").hide();
			$("#group_storage").hide();
			//*********************************** INSERT Task To database **************************
			$("#task_storage_select").change(function() {
				tskval = this.value;
				if (this.value === "0") { 
					$("#addTask_clients").empty();
					$("#addTask_Storage").empty();
					$("#addTask_Backend").empty();
					$("#addTask_TestType").empty();
					$("#addTask_users").empty();
					taskSelect(this.value,formID);
				} else if (this.value === "1") {
					$("#addgrpTask_Storage").empty();
					$("#addgrpTask_Backend").empty();
					$("#addgrpTask_clients").empty();
					taskSelect(this.value,formID);
				}
			});
			console.log(this.id);
            $("#addTask_form").modal();
			//$("#addTask_form").dialog({
			//	resizable : false,
			//	height : 'auto',
			//	width : 'auto',
			//	title : "Adding Task ",
			//	modal : true,
			//	buttons : {
			//		"Submit" : function() {
			//			//***************************************************************************
			//			//$('#addTask_form_data').submit(function(e) {
			//			if ( tskval === "0" ) { //single }'
			//				var clientsIDs  = $('#addTask_clients').val();
			//				var storageID   = $('#addTask_Storage').val();
			//				var backendID   = $('#addTask_Backend').val();
			//				var testTypeID  = $('#addTask_TestType').val();
			//				var userID		= $('#addTask_users').val();
			//				//var form_serialize = $('#single_storage').serialize().replace('&addTask_clients=[0-9]','');
			//				
			//				console.debug("Test Type ID :"+testTypeID);
			//				var formResults  = "type=addTask&addTask_Storage="+storageID+"&addTask_Backend="+backendID+"&addTask_clients="+clientsIDs+"&addTask_TestType="+testTypeID+"&addTask_users="+userID;
			//				
			//				console.debug("Form Results : "+formResults);
			//				console.debug("clientsIDs: "+clientsIDs+",storageID: "+storageID+",backendID: "+backendID+",testTypeID: "+testTypeID)
			//				//event.preventDefault();
			//				
			//				//sumbitForm("type=addTask&"+ $(this).serialize()+ "&addTask_clients=" );
			//				sumbitForm(formResults);
            //                //timeout_init(3000);
			//			} else if ( tskval === "1" ) {
			//					if ($(this).val().length > 3 ) {
			//						console.debug("Task is type=addTask&"+ $('#group_storage').serialize()+ "&addTask_clients=" );
			//					}
			//			}
			//				$('#addTask_form_data').trigger("reset");
			//				return false;
			//			//});
			//			
			//			
			//			//***************************************************************************
			//			$(this).dialog("close");
			//			/*$.ajax({
			//				type : "GET",
			//				url : 'lib/data_new.php',
			//				data : 'data=removeTask&ids='+ taskID,
			//				// cache: false,
			//				success : function(data) {
			//					timeout_init(1000);
			//				}
			//			});*/
			//		},
			//		Cancel : function() {
			//			$(this).dialog("close");
			//			
			//			
			//		}
			//	}
			//});

		}
	});


	// on click remove bottom
	//
	// $("body").on("click", "td", function() {
	//$('#task_list').on('click','td[id^="taskID_"]',function() {
        //
	$('#task_list').on('click','td[id^="removeTask"]',function() {
		// $('#task_list').on('click', 'tr',function() {
		$('#dialog-confirm').empty();
		var taskID = $(this).closest('tr').attr('value');
		// var trid = $(this).val();
		console.debug(taskID);
		$('#dialog-confirm').append('<table style="border: 1px; ">'+ $(this).closest('tr').html()+ '</table>');

                $("#dialog-confirm").modal({onOpen: function (dialog) {
                    dialog.overlay.fadeIn('slow', function () {
                    //dialog.data.hide();
                      dialog.container.fadeIn('slow', function () {
                        dialog.data.slideDown('slow');   
		          $.ajax({
		            type : "GET",
			    url : 'lib/data_new.php',
			    data : 'data=removeTask&ids='+ taskID,
			    // cache: false,
			    success : function(data) {
			      // console.log(data);
			      timeout_init(1000);
			      // window.location.reload();
			    }
                            
                          });
                        });
                    });
                }});
		/*$("#dialog-confirm").dialog({
		  resizable : false,
		  height : 'auto',
		  width : 'auto',
		  title : "Remove Task From List",
		  modal : true,
		  buttons : {
		    "Remove Task" : function() {
		    $(this).dialog("close");
		      $.ajax({
		        type : "GET",
			url : 'lib/data_new.php',
			data : 'data=removeTask&ids='+ taskID,
			// cache: false,
			success : function(data) {
			  // console.log(data);
			  timeout_init(1000);
			  // window.location.reload();
			}
		      });
		    },
		    Cancel : function() {
		      $(this).dialog("close");
		    }
		  }
		});*/
	});
	// click on addTest
	$('#task_list').on('click','td',function() {
	  var thisIDS = $(this).attr('id');
	  var thisClose = $(this).closest('td').attr('id');
	  var thisLast  = $(this).last('td').attr('id');

	  var thisValue = $(this).closest('tr').attr('value');
          if ( thisIDS === "removeTask" || thisIDS === "addingTask" ) {
            return;
          }
	  console.debug("display Task list: " +thisValue + " " + thisClose + " " +thisLast+" "+thisIDS);
          $('#displayTaskLists').modal();
          /*$("#displayTaskLists").dialog({
	    resizable : false,
	    height : 'auto',
	    width : 'auto',
	    title : "Remove Task From List",
	    modal : true,
             buttons: {
	      "Submit" : function() {
              },
	      Cancel : function() {
              }
            }
          });*/
        });

	$('#task_list').on('click','td[id^="addingTask"]',function() {
		
		var thisValue = $(this).closest('tr').attr('value');
		console.debug("Adding Test TO stand : " +thisValue );
		
		$("#task_name").change(function() {
			console.debug("Selected TEST " +this.value)
		});
		
        $('#addTestToTask').modal({
            overlayCss: {backgroundColor:"#fff"}
        
        });
		
		// $("#addTestToTask").dialog({
		//	resizable : false,
		//	height : 'auto',
		//	width : 'auto',
		//	title : "Remove Task From List",
		//	modal : true,
		//	buttons : {
		//		"Submit" : function() {
		//			//$(this).dialog("close");
		//			$.ajax({
		//				type : "GET",
		//				url : 'lib/data_new.php',
		//				data : 'data=removeTask&ids='+ taskID,
		//				// cache: false,
		//				success : function(data) {
		//					// console.log(data);
		//					timeout_init(1000);
		//					// window.location.reload();
		//				}
		//			});
		//		},
		//		Cancel : function() {
		//			$(this).dialog("close");
		//		}
		//	}
		//});
		
	});
	// Submit Data Section
	//
	//
	//
    // Add Task to Table;
    $('#addTask_form_data').submit(function(e) {
        var clients;
        var count = "1";
        var c = "";
        $('#addTask_clients option:selected').each(function() {
            var clientValue = $(this).val();
            var clientLength = $('#addTask_clients :selected').length;
            if (!(clientValue === "undefined" || clientValue === '')) {
                if (count <= "1") {
                    c = clientValue;
                } else {
                    c += ","+ clientValue;
                }
                count++;
            }
        });

        // console.log("dataD " +c);
        // console.log("Adding New Task To Database : Data : type=Task&" + $(this ).serialize() + "&addTask_clients="+c);
        sumbitForm("type=addTask&"+ $(this).serialize()+ "&addTask_clients=" + c);
        window.setTimeout('location.reload()',8000);
        $('#addTask_form_data').trigger("reset");
        return false;
    });

	// submit clients
	$('#addClient_form_data').submit(function(e) {
		// console.log("\nAdding New client To Database : Data :" + $(this).serialize()+"\n");
		sumbitForm("type=addClient&" + $(this).serialize());
		window.setTimeout('location.reload()', 8000);
		$('#addClient_form').trigger("reset");
		return false;
	});
	// submit storage
	$('#addStorage_form_data').submit(function(e) {
		// console.log("\nAdding New Storage To Database: Data :" + $(this).serialize()+"\n");
		var value = sumbitForm("type=addStorage&"+ $(this).serialize());
		// console.log("Return Data from submitForm : "+ value + "\n");
		window.setTimeout('location.reload()', 8000);
		$('#addStorage_form').trigger("reset");
		return false;
	});
	// submit backend Storage
	$('#addBackend_form_data').submit(function(e) {
		sumbitForm("type=addBackend&" + $(this).serialize());
		console.log("\nAdding New Backend Storage : Data :" +$(this).serialize()+"\n");
		window.setTimeout('location.reload()', 8000);
		$('#addBackend_form_data').trigger("reset");
		return false;
	});

	$(".slidingDiv").hide();
	$(".show_hide").show();
	$('.show_hide').click(function() {
		$(".slidingDiv").slideToggle();
	});
});
