<script type="text/javascript">
$(document).ready(function() {
	jQuery("#exception_list").jqGrid({
		url:"<?php echo site_url('assistant/schedule/exception_list');?>",
		editurl:"<?php echo site_url('assistant/schedule/edit_exception_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Day','Start Time','End Time','Title','Reason'],
		colModel:[
			{name:'repeat_id',index:'repeat_id',width:1,hidden:true},
			{name:'repeat_day',index:'repeat_day',width:100,editable:true,formatter:capsfn,editrules:{required:true},edittype:'select',editoptions:{value:"sunday:Sunday;monday:Monday;tuesday:Tuesday;wednesday:Wednesday;thrusday:Thrusday;friday:Friday;saturday:Saturday"},formoptions:{elmsuffix:"(*)"}},
			{	name:'repeat_start_time',
				index:'repeat_start_time',
				width:100,
				editable:true,
				editrules:{required:true},
				formoptions:{elmsuffix:"(*)"}
			},
			{	name:'repeat_end_time',
				index:'repeat_end_time',
				width:100,
				editable:true,
				editrules:{required:true},
				formoptions:{elmsuffix:"(*)"}
			},
			{name:'title',index:'title',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'reason',index:'reason',width:400,editable:true,edittype:'textarea',editoptions:{rows:'4',cols:'20'}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#exception_list_pager'),
		sortname: 'repeat_day',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Repeating Schedule Events",
	 	emptyrecords:"No repeating events",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#exception_list_pager',{edit:false,add:false,del:false
	});
	
	function capsfn (cellvalue, options, rowObject){
		if (cellvalue == 'sunday') {
			return 'Sunday';
		}
		if (cellvalue == 'monday') {
			return 'Monday';
		}
		if (cellvalue == 'tuesday') {
			return 'Tuesday';
		}
		if (cellvalue == 'wednesday') {
			return 'Wednesday';
		}
		if (cellvalue == 'thursday') {
			return 'Thursday';
		}
		if (cellvalue == 'friday') {
			return 'Friday';
		}
		if (cellvalue == 'saturday') {
			return 'Saturday';
		}
	}
	
	$("#add_exception1").click(function(){
		jQuery("#exception_list").editGridRow("new",{
			closeAfterAdd:true,
			width:'650',
			bottominfo:'Fields marked in (*) are required.',
			onInitializeForm : function(formid){
				$("#repeat_start_time",formid).timepicker({
					'scrollDefaultNow': true,
					'timeFormat': 'h:i A',
					'step': 15
				});
				$("#repeat_end_time",formid).timepicker({
					'scrollDefaultNow': true,
					'timeFormat': 'h:i A',
					'step': 15
				});
			}
		});	
	});

	$("#edit_exception1").click(function(){
		var item = jQuery("#exception_list").getGridParam('selrow');
		if(item){ 
			jQuery("#exception_list").editGridRow(item,{
				closeAfterEdit:true,
				width:'650',
				bottominfo:'Fields marked in (*) are required.',
				onInitializeForm : function(formid){
					$("#repeat_start_time",formid).timepicker({
						'scrollDefaultNow': true,
						'timeFormat': 'h:i A',
						'step': 15
					});
					$("#repeat_end_time",formid).timepicker({
						'scrollDefaultNow': true,
						'timeFormat': 'h:i A',
						'step': 15
					});
				}
			});
		} else {
			$.jGrowl("Please select exception to edit!");
		}
	});

	$("#delete_exception1").click(function(){
		var item = jQuery("#exception_list").getGridParam('selrow');
		if(item){ 
			jQuery("#exception_list").delGridRow(item);
			jQuery("#exception_list").delRowData(item);
		} else {
			$.jGrowl("Please select exception to delete!");
		}
	});
});
</script>

<table id="exception_list" class="scroll" cellpadding="0" cellspacing="0"></table>
<div id="exception_list_pager" class="scroll" style="text-align:center;"></div><br>
<input type="button" id="add_exception1" value="Add Exception" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="edit_exception1" value="Edit Exception" class="ui-button ui-state-default ui-corner-all"/>
<input type="button" id="delete_exception1" value="Delete Exception" class="ui-button ui-state-default ui-corner-all"/>
