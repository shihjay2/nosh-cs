<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("patient/chartmenu");?>',
		redirect_url: '<?php echo site_url("logout");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: false
	});
	$("#form_button_div").hide();
	jQuery("#forms").jqGrid({
		url:"<?php echo site_url('patient/chartmenu/forms_grid/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Form','Form ID','Date Completed'],
		colModel:[
			{name:'template_id',index:'template_id',width:1,hidden:true},
			{name:'template_name',index:'template_name',width:400},
			{name:'forms_id',index:'forms_id',width:1,hidden:true},
			{name:'forms_date',index:'forms_date',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#forms_pager'),
		sortname: 'template_name',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Click on a form:",
	 	height: "100%",
	 	onSelectRow: function(id){
	 		$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/chartmenu/get_form');?>/" + id,
				success: function(data){
					$("#form_array").val(data);
					$('#form_content').html('');
					var json_object = JSON.parse(data);
					$('#form_content').dform(json_object);
					$("#form_template_id").val(id);
					$(".patient_form_div").css("padding","5px");
					$('.patient_form_buttonset').buttonset();
					$('input.form_other[type="checkbox"]').button();
					$('.patient_form_text input[type="text"]').css("width","280px");
					$('.patient_form_div select').addClass("text ui-widget-content ui-corner-all");
					$(".form_select").chosen();
					var row = jQuery("#forms").getGridParam('selrow');
					var forms_id = jQuery('#forms').jqGrid('getCell', row, 'forms_id');
					if (forms_id != "") {
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('patient/chartmenu/get_form_data');?>/" + forms_id,
							success: function(data){
								var json_object = JSON.parse(data);
								$("#form_content").populate(json_object);
								$('#form_dialog').dialog('option', 'title', "Fill out the " + json_object.forms_title + " Form:");
								$(".patient_form_buttonset input").button('refresh');
							}
						});
					} else {
						$('#form_dialog').dialog('option', 'title', "Fill out the " + json_object.html[2].value + " Form:");
					}
					$("#form_dialog").dialog('open');
				}
			});
	 	},
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#forms_pager',{search:false,edit:false,add:false,del:false});
	$("#form_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'Submit': function() {
				var id = $("#form_template_id").val();
				var content = JSON.stringify($("#form_content").serializeJSON());
				var title = $("#form_forms_title").val();
				var destination = $("#form_forms_destination").val();
				var text = "Form title: " + $("#form_forms_title").val() + "\n";
				var d = new Date();
				var date = d.toISOString();
				text += "Form completed by patient on " + date + "\n";
				text += "********************************************\n";
				$(".patient_form_div").each(function() {
					var a = $(this).attr('class');
					var b = $(this).attr('id');
					if (a == "ui-dform-div patient_form_div patient_form_buttonset ui-buttonset") {
						$("#" + b + " input:checked").each(function() {
							text += $(this).val() + "\n";
						});
					}
					if (a == "ui-dform-div patient_form_div patient_form_text") {
						text += $("#" + b + "_label").html() + ": " + $("#" + b + "_text").val() + "\n";
					}
					if (a == "ui-dform-div patient_form_div") {
						text += $("#" + b + "_select").val() + "\n";
					}
				});
				var array = $("#form_array").val();
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('patient/chartmenu/save_form_data');?>",
					data: "template_id=" + id + "&forms_content=" + content + "&forms_title=" + title + "&forms_destination=" + destination + "&forms_content_text=" + text + "&array=" + array,
					success: function(data){
						$("#form_template_id").val();
						$("#form_button_div").hide();
						$('#form_content').clearForm();
						$('#form_content').html('');
						$("#form_array").val();
						$.jGrowl(data);
						jQuery("#forms").trigger("reloadGrid");
						$("#form_dialog").dialog('close');
					}
				});
			},
			'Clear' : function() {
				$('#form_content').clearForm();
				$(".patient_form_buttonset input").button('refresh');
			},
			Cancel: function() {
				$("#form_template_id").val();
				$("#form_button_div").hide();
				$('#form_content').clearForm();
				$('#form_content').html('');
				$("#form_array").val();
				$("#form_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$('#form_content').clearForm();
		}
	});
});
</script>
<div id="heading2"></div>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Patient Forms</h4>
		<table id="forms" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="forms_pager" class="scroll" style="text-align:center;"></div>
	</div>
</div>
<div id="form_dialog" title="">
	<div class="form_template_div">
		<input type="hidden" id="form_array"/>
		<br><form id="form_content" class="ui-widget"></form>
	</div>
</div>
