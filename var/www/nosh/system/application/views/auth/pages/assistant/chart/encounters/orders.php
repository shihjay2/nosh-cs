<form id="orders_form">
	<button type="button" id="print_orders">Print Orders Summary</button>
	<input type="hidden" name="orders_plan_old" id="orders_plan_old"/>
	<input type="hidden" name="orders_duration_old" id="orders_duration_old"/>
	<input type="hidden" name="orders_followup_old" id="orders_followup_old"/>
	<hr class="ui-state-default"/>
	<table>
		<tr>
			<td valign="top">
				<table>
					<tr>
						<td valign="top">
							Recommendations:<br>
							<button type="button" id="orders_plan_reset">Clear</button><br>
							<button type="button" id="orders_plan_instructions">Instructions</button><br>
							<button type="button" id="encounter_letter">Letter</button>
						</td>
						<td valign="top">
							<textarea style="width:300px" rows="10" name="plan" id="orders_plan" class="text ui-widget-content ui-corner-all"></textarea><br><br>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Follow Up:<br><button type="button" id="orders_followup_reset">Clear</button><br>
						</td>
						<td valign="top">
							<textarea style="width:300px" rows="3" name="followup" id="orders_followup" class="text ui-widget-content ui-corner-all"></textarea><br><br>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td valign="top">
							Face-to-face time (in minutes), if greater than 50% of the visit:<br>
							<input type="text" style="width:100px" name="duration" id="orders_duration" class="text ui-widget-content ui-corner-all">
							<button type="button" id="orders_duration_reset">Clear</button>
						</td>
					</tr>
				</table>
			<td valign="top">
				<table>
					<tr>
						<td><div id="button_orders_labs_status" class="orders_tooltip"><?php echo $labs_status;?></div></td>
						<td><input type="button" id="button_orders_labs" value="Lab" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_rad_status" class="orders_tooltip"><?php echo $rad_status;?></div></td>
						<td><input type="button" id="button_orders_rad" value="Imaging" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_cp_status" class="orders_tooltip"><?php echo $cp_status;?></div></td>
						<td><input type="button" id="button_orders_cp" value="Cardiopulmonary" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_ref_status" class="orders_tooltip"><?php echo $ref_status;?></div></td>
						<td><input type="button" id="button_orders_ref" value="Referrals" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_sup_status" class="orders_tooltip"><?php echo $sup_status;?></div></td>
						<td><input type="button" id="button_orders_supplements" value="Supplements" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_imm_status" class="orders_tooltip"><?php echo $imm_status;?></div></td>
						<td><input type="button" id="button_orders_imm" value="Immunizations" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<div id="orders_plan_instructions_dialog" title="Patient Instructions">
	<textarea style="width:750px" rows="8" name="instructions_chosen" id="instructions_chosen" class="text ui-widget-content ui-corner-all" placeholder="Patient instructions."></textarea>
</div>
<div id="instructions_dialog_load1" title="Checking...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading Vivacare Patient Education Materials.
</div>
<script type="text/javascript">
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('assistant/encounters/get_orders');?>",
		dataType: "json",
		success: function(data){
			$("#orders_plan").val(data.plan);
			$("#orders_duration").val(data.duration);
			$("#orders_followup").val(data.followup);
			$("#orders_plan_old").val(data.plan);
			$("#orders_duration_old").val(data.duration);
			$("#orders_followup_old").val(data.followup);
		}
	});
	$("#print_orders").button({icons: {primary: "ui-icon-print"}}).click(function(){
		window.open("<?php echo site_url('assistant/encounters/print_orders');?>");
	});
	function split( val ) {
		return val.split( /\n\s*/ );
	}
	function extractLast( term ) {
		return split( term ).pop();
	}
	$("#instructions_dialog_load1").dialog({
		height: 100,
		width: 350,
		autoOpen: false, 
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#orders_plan_instructions_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 350, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#instructions_dialog_load1").dialog('open');
			$.ajax({
				url: "<?php echo site_url('search/vivacare_data');?>",
				dataType: "json",
				type: "POST",
				success: function(data){
					$("#instructions_dialog_load1").dialog("close");
					if(data.response =='true'){
						var cache = data.message;
						$("#instructions_chosen").catcomplete({
							source: function (req, add) {
								add($.ui.autocomplete.filter(cache, extractLast( req.term )));
							},
							search: function() {
								var term = extractLast( this.value );
								if ( term.length < 2 ) {
									return false;
								}
							},
							focus: function() {
								return false;
							},
							select: function(event, ui){
								var terms = split( this.value );
								terms.pop();
								terms.push( ui.item.value );
								terms.push( "" );
								this.value = terms.join( "\n" );
								$("#instructions_dialog_load1").dialog('open');
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('assistant/chartmenu/print_vivacare');?>/" + ui.item.link,
									dataType: "json",
									success: function(data){
										if (data.message == 'OK') {
											$.ajax({
												type: "POST",
												url: "<?php echo site_url('assistant/chartmenu/view_documents1');?>/" + data.id,
												dataType: "json",
												success: function(data){
													//$('#embedURL').PDFDoc( { source : data.html } );
													$("#embedURL").html(data.html);
													$("#document_filepath").val(data.filepath);
													$("#instructions_dialog_load1").dialog('close');
													$("#documents_view_dialog").dialog('open');
												}
											});
										} else {
											$.jGrowl(data.message);
										}
									}
								});
								return false;
							}
						}).attr('placeholder','Type in terms for Vivacare Patient Education Handouts');
					}
				}
			});
		},
		buttons: {
			'Save': function() {
				var b = $("#instructions_chosen").val();
				if (b != '') {
					var old = $("#orders_plan").val();
					if(old){
						var pos = old.lastIndexOf('\n');
						if (pos == -1) {
							var old1 = old + '\n';
						} else {
							var a = old.slice(pos);
							if (a == '') {
								var old1 = old;
							} else {
								var old1 = old + '\n';
							}
						}
					} else {
						var old1 = '';
					}
					var intro = 'Patient Instructions Given: ';
					$("#orders_plan").val(old1+intro+b);
				}
				$("#instructions_chosen").val('');
				$("#orders_plan_instructions_dialog").dialog('close');
			},
			Cancel: function() {
				$("#instructions_chosen").val('');
				$("#orders_plan_instructions_dialog").dialog('close');
			}
		},
		close: function(event, ui) {
			var b = $("#instructions_chosen").val();
			if (b != '') {
				if(confirm('Are you sure you want to close this window?  There instructions that have not been saved.')){ 
					$("#instructions_chosen").val('');
				}
			}
		}
	});
	$("#orders_plan").focus();
	$('#orders_plan_reset').button();
	$('#orders_plan_reset').click(function(){
		$("#orders_plan").val('');
	});
	$('#orders_plan_instructions').button();
	$('#orders_plan_instructions').click(function(){
		$("#orders_plan_instructions_dialog").dialog('open');
	});
	$("#encounter_letter").button();
	$("#encounter_letter").click(function() {
		$("#letter_dialog").dialog('open');
		$("#letter_eid").val('1');
	});
	$('#orders_duration_reset').button();
	$('#orders_duration_reset').click(function(){
		$("#orders_duration").val('');
	});
	$("#orders_followup_reset").button();
	$('#orders_followup_reset').click(function(){
		$("#orders_followup").val('');
	});
	$("#button_orders_labs").button();
	$("#button_orders_labs").click(function() {
		$("#edit_message_lab_form").show('fast');
		$("#save_lab_helper_label").html('Close Helper');
		$("#messages_lab_t_messages_id").val('');
		$("#messages_lab_origin").val('encounter');
		$("#messages_lab_header").show('fast');
		jQuery("#messages_lab_list").trigger("reloadGrid");
		$("#messages_lab_dialog").dialog('open');
	});
	$('.orders_tooltip').tooltip({
		items: ".orders_tooltip",
		hide: false,
		show: false,
		content: "Loading...",
		position: { my: "right+15 bottom", at: "left top", collision: "flipfit" },
		open: function(event, ui) {
			var elem = $(this);
			var id = $(this).attr("id");
			var parts = id.split('_');
			var id1 = parts[2];
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('assistant/encounters/tip_orders/');?>/" + id1,
				success: function(data) {
					elem.tooltip('option', 'content', data);
					elem.tooltip("option","position",{ my: "right+15 bottom", at: "left top", collision: "flipfit" });
				},
			});
		}
	})
	$("#button_orders_rad").button();
	$("#button_orders_rad").click(function() {
		$("#edit_message_rad_form").show('fast');
		$("#save_rad_helper_label").html('Close Helper');
		$("#messages_rad_t_messages_id").val('');
		$("#messages_rad_origin").val('encounter');
		$("#messages_rad_header").show('fast');
		jQuery("#messages_rad_list").trigger("reloadGrid");
		$("#messages_rad_dialog").dialog('open');
	});
	$("#button_orders_cp").button();
	$("#button_orders_cp").click(function() {
		$("#edit_message_cp_form").show('fast');
		$("#save_cp_helper_label").html('Close Helper');
		$("#messages_cp_t_messages_id").val('');
		$("#messages_cp_origin").val('encounter');
		$("#messages_cp_header").show('fast');
		jQuery("#messages_cp_list").trigger("reloadGrid");
		$("#messages_cp_dialog").dialog('open');
	});
	$("#button_orders_ref").button();
	$("#button_orders_ref").click(function() {
		$("#edit_message_ref_form").show('fast');
		$("#save_ref_helper_label").html('Close Helper');
		$("#messages_ref_t_messages_id").val('');
		$("#messages_ref_origin").val('encounter');
		$("#messages_ref_header").show('fast');
		jQuery("#messages_ref_list").trigger("reloadGrid");
		$("#messages_ref_dialog").dialog('open');
	});
	$("#button_orders_supplements").button();
	$("#button_orders_supplements").click(function() {
		$("#supplement_origin_orders").val("Y");
		$("#supplement_origin_orders1").val("Y");
		$("#supplements_list_dialog").dialog('open');
		$("#orders_supplements_header").show();
		$("#orders_supplements").focus();
	});
	$("#button_orders_imm").button();
	$("#button_orders_imm").click(function() {
		$("#immunizations_list_dialog").dialog('open');
		$("#orders_imm_header").show('fast');
		$('#edit_immunization_form').hide('fast');
		$('#imm_order').show('fast');
		$('#imm_menu').hide('fast');
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('start/check_mtm_extension');?>",
		dataType: "json",
		success: function(data){
			if (data.response == 'y') {
				var button = '<br><button type="button" id="encounter_mtm">MTM</button>';
				$("#recommendations_buttons").append(button);
				$("#encounter_mtm").button().click(function() {
					$("#mtm_dialog").dialog('open');
					$("#mtm_origin").val('encounter');
				});
			}
		}
	});
	function orders_autosave() {
		var old8a = $("#orders_plan_old").val();
		var new8a = $("#orders_plan").val();
		var old8b = $("#orders_duration_old").val();
		var new8b = $("#orders_duration").val();
		var old8c = $("#orders_followup_old").val();
		var new8c = $("#orders_followup").val();
		if (old8a != new8a || old8b != new8b || old8c != new8c) {
			var orders_str = $("#orders_form").serialize();
			if(orders_str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('assistant/encounters/orders_save');?>",
					data: orders_str,
					success: function(data){
						$.jGrowl(data);
						var a = $("#orders_plan").val();
						var b = $("#orders_duration").val();
						var c = $("#orders_followup").val();
						$("#orders_plan_old").val(a);
						$("#orders_duration_old").val(b);
						$("#orders_followup_old").val(c);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	}
	setInterval(orders_autosave, 10000);
</script>
