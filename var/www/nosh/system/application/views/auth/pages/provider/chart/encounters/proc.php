<form id="procedure_form">
	<button type="button" id="save_procedure">Save</button>
	<button type="button" id="template_procedure">Add/Update Template</button>
	<button type="button" id="cancel_procedure">Cancel</button>
	<input type="hidden" name="procedure_type_old" id="procedure_type_old"/>
	<input type="hidden" name="procedure_cpt_old" id="procedure_cpt_old"/>
	<input type="hidden" name="procedure_description_old" id="procedure_description_old"/>
	<input type="hidden" name="procedure_complications_old" id="procedure_complications_old"/>
	<input type="hidden" name="procedure_ebl_old" id="procedure_ebl_old"/>
	<hr class="ui-state-default"/>
	<input type="hidden" name="procedurelist_id" id="procedurelist_id">
	<table>
		<tr>
			<td valign="top">Type:</td>
			<td valign="top"><input type="text" style="width:500px" name="procedure_type" id="procedure_type" class="text ui-widget-content ui-corner-all"></td>
			<td valign="top"><input type="button" id="procedure_type_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
		</tr>
		<tr>
			<td valign="top">CPT:</td>
			<td valign="top"><input type="text" style="width:500px" name="procedure_cpt" id="procedure_cpt" class="text ui-widget-content ui-corner-all"></td>
			<td valign="top"><input type="button" id="procedure_cpt_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
		</tr>
		<tr>
			<td valign="top">Description:</td>
			<td><textarea style="width:500px" rows="10" name="procedure_description" id="procedure_description" class="text ui-widget-content ui-corner-all"></textarea></td>
			<td valign="top"><input type="button" id="procedure_description_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
		</tr>
		<tr>
			<td valign="top">Complications:</td>
			<td><textarea style="width:500px" rows="1" name="procedure_complications" id="procedure_complications" class="text ui-widget-content ui-corner-all"></textarea></td>
			<td valign="top"><input type="button" id="procedure_complications_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
		</tr>
		<tr>
			<td valign="top">Estimated Blood Loss:</td>
			<td><textarea style="width:500px" rows="1" name="procedure_ebl" id="procedure_ebl" class="text ui-widget-content ui-corner-all"></textarea></td>
			<td valign="top"><input type="button" id="procedure_ebl_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_proc');?>",
		dataType: "json",
		success: function(data){
			$("#procedure_type").val(data.proc_type);
			$("#procedure_description").val(data.proc_description);
			$("#procedure_complications").val(data.proc_complications);
			$("#procedure_ebl").val(data.proc_ebl);
			$("#procedure_cpt").val(data.proc_cpt);
			$("#procedure_type_old").val(data.proc_type);
			$("#procedure_description_old").val(data.proc_description);
			$("#procedure_complications_old").val(data.proc_complications);
			$("#procedure_ebl_old").val(data.proc_ebl);
			$("#procedure_cpt_old").val(data.proc_cpt);
		}
	});
	$("#save_procedure").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#template_procedure").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$("#cancel_procedure").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#procedure_type").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/procedure_type');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
			$("#procedure_description").val(ui.item.procedure_description);
			$("#procedure_complications").val(ui.item.procedure_complications);
			$("#procedure_ebl").val(ui.item.procedure_ebl);
			$("#procedurelist_id").val(ui.item.procedurelist_id);
			$("#procedure_cpt").val(ui.item.cpt);
		}
	});
	$("#procedure_cpt").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/cpt');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			$(this).end().val(ui.item.value);
		}
	});
	$("#save_procedure").click(function(){
		var a = $("#procedure_type");
		var b = $("#procedure_description");
		var c = $("#procedure_complications");
		var d = $("#procedure_ebl");
		var e = $("#procedure_cpt");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Type");
		bValid = bValid && checkEmpty(b,"Description");
		bValid = bValid && checkEmpty(c,"Complications");
		bValid = bValid && checkEmpty(d,"Estimated Blood Loss");
		bValid = bValid && checkEmpty(e,"CPT");
		if (bValid) {
			var str = $("#procedure_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/proc_save');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
						var a = $("#procedure_type").val();
						var b = $("#procedure_description").val();
						var c = $("#procedure_complications").val();
						var d = $("#procedure_ebl").val();
						var e = $("#procedure_cpt").val();
						$("#procedure_type_old").val(a);
						$("#procedure_description_old").val(b);
						$("#procedure_complications_old").val(c);
						$("#procedure_ebl_old").val(d);
						$("#procedure_cpt_old").val(e);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_procedure").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_proc');?>",
			dataType: "json",
			success: function(data){
				$("#procedure_type").val(data.proc_type);
				$("#procedure_description").val(data.proc_description);
				$("#procedure_complications").val(data.proc_complications);
				$("#procedure_ebl").val(data.proc_ebl);
				$("#procedure_cpt").val(data.cpt);
				$("#procedure_type_old").val(data.proc_type);
				$("#procedure_description_old").val(data.proc_description);
				$("#procedure_complications_old").val(data.proc_complications);
				$("#procedure_ebl_old").val(data.proc_ebl);
				$("#procedure_cpt_old").val(data.proc_cpt);
			}
		});
	});
	$("#template_procedure").click(function(){
		var a = $("#procedure_type");
		var bValid = true;
		bValid = bValid && checkEmpty(a,"Type");
		if (bValid) {
			var str = $("#procedure_form").serialize();
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/proc_template');?>",
					data: str,
					success: function(data){
						$.jGrowl(data);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$('#procedure_type_reset').button();
	$('#procedure_type_reset').click(function(){
		$("#procedure_type").val('');
		$("#procedurelist_id").val('');
	});
	$('#procedure_cpt_reset').button();
	$('#procedure_cpt_reset').click(function(){
		$("#procedure_cpt").val('');
	});
	$('#procedure_description_reset').button();
	$('#procedure_description_reset').click(function(){
		$("#procedure_description").val('');
	});
	$('#procedure_complications_reset').button();
	$('#procedure_complications_reset').click(function(){
		$("#procedure_complications").val('');
	});
	$('#procedure_ebl_reset').button();
	$('#procedure_ebl_reset').click(function(){
		$("#procedure_ebl").val('');
	});
	function proc_autosave() {
		var old6a = $("#procedure_type_old").val();
		var new6a = $("#procedure_type").val();
		var old6b = $("#procedure_cpt_old").val();
		var new6b = $("#procedure_cpt").val();
		var old6c = $("#procedure_description_old").val();
		var new6c = $("#procedure_description").val();
		var old6d = $("#procedure_complications_old").val();
		var new6d = $("#procedure_complications").val();
		var old6e = $("#procedure_ebl_old").val();
		var new6e = $("#procedure_ebl").val();
		if (old6a != new6a || old6b != new6b || old6c != new6c || old6d != new6d || old6e != new6e) {
			var proc_str = $("#procedure_form").serialize();
			if(proc_str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/proc_save');?>",
					data: proc_str,
					success: function(data){
						$.jGrowl(data);
						var a = $("#procedure_type").val();
						var b = $("#procedure_description").val();
						var c = $("#procedure_complications").val();
						var d = $("#procedure_ebl").val();
						var e = $("#procedure_cpt").val();
						$("#procedure_type_old").val(a);
						$("#procedure_description_old").val(b);
						$("#procedure_complications_old").val(c);
						$("#procedure_ebl_old").val(d);
						$("#procedure_cpt_old").val(e);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	}
	setInterval(proc_autosave, 10000);
</script>
