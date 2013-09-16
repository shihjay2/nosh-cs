<div id="supplements_list_dialog" title="Supplements">
	<div id="orders_supplements_header" style="display:none">
		<button type="button" id="save_orders_supplements" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-arrowthickstop-1-s"></span>
			<span style="float:right;" class="ui-button-text">Save Supplement Order</span>
		</button> 
		<button type="button" id="cancel_orders_supplements_helper" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
			<span style="float:right;" class="ui-button-text">Cancel</span>
		</button> 
		<hr class="ui-state-default"/>
	</div>
	<div id="oh_supplements_header" style="display:none">
		<button type="button" id="save_oh_supplements" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
			<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-arrowthickstop-1-s"></span>
			<span style="float:right;" class="ui-button-text">Save Supplements List</span>
		</button>
		<hr class="ui-state-default"/>
	</div>
	<table id="supplements" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="supplements_pager" class="scroll" style="text-align:center;"></div><br>
	<input type="hidden" name="supplement_text" id="supplement_text"/>
	<input type="hidden" name="supplement_inactivate_text" id="supplement inactivate_text"/>
	<input type="hidden" name="supplement_reactivate_text" id="supplement_reactivate_text"/>
	<form name="edit_sup_form" id="edit_sup_form" style="display: none">
		<input type="hidden" name="sup_id" id="sup_id"/>
		<fieldset class="ui-state-default ui-corner-all">
			<legend>Supplement</legend>
			<table>
				<tbody>
					<tr>
						<td colspan="2">Supplement:<br><input type="text" name="sup_supplement" id="sup_supplement" style="width:356px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Dosage:<br><input type="text" name="sup_dosage" id="sup_dosage" style="width:117px"class="text ui-widget-content ui-corner-all"/></td>
						<td>Unit:<br><input type="text" name="sup_dosage_unit" id="sup_dosage_unit" style="width:117px" class="text ui-widget-content ui-corner-all"/></td>
						<td>
							<button type="button" id="search_db_supplement" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-search"></span>
								<span style="float:right;" class="ui-button-text">Database</span>
							</button>
						</td>
					</tr>
					<tr>
						<td>Sig:<br><input type="text" name="sup_sig" id="sup_sig" style="width:235px" class="text ui-widget-content ui-corner-all"/></td>
						<td>Route<br><select id ="sup_route" name="sup_route" class="text ui-widget-content ui-corner-all"></select></td>
						<td colspan="2">Frequency:<br><input type="text" name="sup_frequency" id="sup_frequency" style="width:240px"  class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4">Special Instructions:<br><input type="text" name="sup_instructions" id="sup_instructions" style="width:600px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4">Reason:<br><input type="text" name="sup_reason" id="sup_reason" style="width:600px" class="text ui-widget-content ui-corner-all"/></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4">Date Active:<br><input type="text" name="sup_date_active" id="sup_date_active" class="text ui-widget-content ui-corner-all"/></td> 
						<td>
							<button type="button" id="save_supplement" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-disk"></span>
								<span style="float:right;" class="ui-button-text">Save</span>
							</button><br>
							<button type="button" id="cancel_supplement" class="ui-button ui-button-text-icon ui-widget ui-state-default ui-corner-all">
								<span style="float:left;" class="ui-button-icon-primary ui-icon ui-icon-close"></span>
								<span style="float:right;" class="ui-button-text">Cancel</span>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset><br>
	</form>
	<table id="supplements_inactive" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="supplements_inactive_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<script type="text/javascript">
	$("#supplements_list_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		close: function(event, ui) {
			$('#edit_sup_form').clearForm();
			$('#edit_sup_form').hide('fast');
			$('#oh_supplements_header').hide('fast');
		}
	});
	$("#supplements_list").click(function() {
		$("#supplements_list_dialog").dialog('open');
		$("#oh_supplements_header").hide('fast');
	});
	$("#sup_date_active").mask("99/99/9999");
	$("#sup_date_active").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#sup_route").addOption({"by mouth":"PO","per rectum":"PR","subcutaneously":"SC","intramuscularly":"IM","intravenously":"IV"}, false);
	$("#sup_route").selectOptions();
	var sup_cache = {};
	$("#sup_supplement").autocomplete({
		source: function (req, add){
			if (req.term in sup_cache){
				add(sup_cache[req.term]);
				return;
			}
			$.ajax({
				url: "<?php echo site_url('search/supplements');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						sup_cache[req.term] = data.message;
						add(data.message);
					}				
				}
			});
		},
		minLength: 3
	});
	$("#sup_dosage").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/sup_dosage');?>",
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
		minLength: 0,
		select: function(event, ui){
			$("#sup_dosage_unit").val(ui.item.unit);
		}
	});
	$("#sup_dosage").click(function(){
		var sup_name = $("#sup_supplement").val();
		if (sup_name == '') {
			$.jGrowl('Supplement field empty!');
		} else {
			$("#sup_dosage").autocomplete("search", sup_name);
		}
	});
	$("#sup_sig").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/sup_sig');?>",
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
		minLength: 1
	});
	$("#sup_frequency").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/sup_frequency');?>",
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
		minLength: 1
	});
	$("#sup_instructions").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/sup_instructions');?>",
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
		minLength: 3
	});
	$("#sup_reason").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/sup_reason');?>",
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
		minLength: 3
	});
	jQuery("#supplements").jqGrid({
		url:"<?php echo site_url('billing/chartmenu/supplements/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Active','Supplement','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason'],
		colModel:[
			{name:'sup_id',index:'sup_id',width:1,hidden:true},
			{name:'sup_date_active',index:'sup_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_supplement',index:'sup_supplement',width:260},
			{name:'sup_dosage',index:'sup_dosage',width:50},
			{name:'sup_dosage_unit',index:'sup_dosage_unit',width:50},
			{name:'sup_sig',index:'sup_sig',width:50},
			{name:'sup_route',index:'sup_route',width:1,hidden:true},
			{name:'sup_frequency',index:'sup_frequency',width:205},
			{name:'sup_instructions',index:'sup_instructions',width:1,hidden:true},
			{name:'sup_reason',index:'sup_reason',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#supplements_pager'),
		sortname: 'sup_date_active',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Supplements",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#supplements_pager',{search:false,edit:false,add:false,del:false});
	jQuery("#supplements_inactive").jqGrid({
		url:"<?php echo site_url('billing/chartmenu/supplements_inactive/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date Active','Supplement','Dosage','Unit','SIG','Route','Frequency','Special Instructions','Reason'],
		colModel:[
			{name:'sup_id',index:'sup_id',width:1,hidden:true},
			{name:'sup_date_active',index:'sup_date_active',width:100,formatter:'date',formatoptions:{srcformat:"ISO8601Long", newformat: "ISO8601Short"}},
			{name:'sup_supplement',index:'sup_supplement',width:260},
			{name:'sup_dosage',index:'sup_dosage',width:50},
			{name:'sup_dosage_unit',index:'sup_dosage_unit',width:50},
			{name:'sup_sig',index:'sup_sig',width:50},
			{name:'sup_route',index:'sup_route',width:1,hidden:true},
			{name:'sup_frequency',index:'sup_frequency',width:205},
			{name:'sup_instructions',index:'sup_instructions',width:1,hidden:true},
			{name:'sup_reason',index:'sup_reason',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#supplements_inactive_pager'),
		sortname: 'sup_date_active',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Inactive Medications",
	 	height: "100%",
	 	hiddengrid: true,
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#supplements_inactive_pager',{search:false,edit:false,add:false,del:false});
	$("#add_sup").click(function(){
		$('#edit_sup_form').clearForm();
		var currentDate = getCurrentDate();
		$('#sup_date_active').val(currentDate);
		$('#edit_sup_form').show('fast');
		$("#sup_supplement").focus();
	});
	$("#edit_sup").click(function(){
		var item = jQuery("#supplements").getGridParam('selrow');
		if(item){
			jQuery("#supplements").GridToForm(item,"#edit_sup_form");
			var date = $('#sup_date_active').val();
			var edit_date = editDate(date);
			$('#sup_date_active').val(edit_date);
			$('#edit_sup_form').show('fast');
			$("#sup_supplement").focus();
		} else {
			$.jGrowl("Please select supplement to edit!")
		}
	});
	$("#inactivate_sup").click(function(){
		var item = jQuery("#supplements").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/chartmenu/inactivate_supplement');?>",
				data: "sup_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#supplement_inactivate_text').val();
					$('#supplement_inactivate_text').val(old + '\n' + data.medtext);
					jQuery("#supplements").trigger("reloadGrid");
					jQuery("#supplements_inactive").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select supplement to inactivate!")
		}
	});
	$("#delete_sup").click(function(){
		var item = jQuery("#supplements").getGridParam('selrow');
		if(item){
			if(confirm('Are you sure you want to delete this supplement?  This is not recommended unless entering the supplement was a mistake!')){ 
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/delete_supplement');?>",
					data: "sup_id=" + item,
					success: function(data){
						$.jGrowl(data);
						jQuery("#supplements").trigger("reloadGrid");
						jQuery("#supplements_inactive").trigger("reloadGrid");
					}
				});
			}
		} else {
			$.jGrowl("Please select supplement to inactivate!")
		}
	});
	$("#reactivate_sup").click(function(){
		var item = jQuery("#supplements_inactive").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('billing/chartmenu/reactivate_supplement');?>",
				data: "sup_id=" + item,
				dataType: "json",
				success: function(data){
					$.jGrowl(data.message);
					var old = $('#supplement_reactivate_text').val();
					$('#supplement_reactivate_text').val(old + '\n' + data.medtext);
					jQuery("#supplements_inactive").trigger("reloadGrid");
					jQuery("#supplements").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select supplement to reactivate!")
		}
	});
	$("#search_db_supplement").click(function(){
		window.open("http://dietarysupplements.nlm.nih.gov/dietary/index.jsp");
	});
	$("#save_supplement").click(function(){
		var supplement = $("#sup_supplement");
		var bValid = true;
		bValid = bValid && checkEmpty(supplement,"Supplement");
		if (bValid) {
			var str = $("#edit_sup_form").serialize();		
			if(str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/chartmenu/edit_supplement');?>",
					data: str,
					dataType: "json",
					success: function(data){
						if (data.message == 'Close Chart') {
							window.location = "<?php echo site_url();?>";
						} else {
							$.jGrowl(data.message);
							var old = $('#supplement_text').val();
							$('#supplement_text').val(old + '\n' + data.medtext);
							jQuery("#supplements").trigger("reloadGrid");
							$('#edit_sup_form').clearForm();
							$('#edit_sup_form').hide('fast');
						}
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	});
	$("#cancel_supplement").click(function(){
		$('#edit_sup_form').clearForm();
		$('#edit_sup_form').hide('fast');
	});
	$('#save_orders_supplements').click(function(){
		var a = $("#supplement_text").val();
		var b = $("#supplement_inactivate_text").val();
		var c = $("#supplement_reactivate_text").val();
		if(a){
			var a1 = 'SUPPLEMENTS ADVISED:  ' + a + '\n\n';	
		} else {
			var a1 = '';
		}
		if(b){
			var b1 = 'DISCONTINUED SUPPLEMENTS:  ' + b + '\n\n';	
		} else {
			var b1 = '';
		}
		if(c){
			var c1 = 'REINSTATED SUPPLEMENTS:  ' + c + '\n\n';	
		} else {
			var c1 = '';
		}
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/encounters/orders_sup_save');?>",
			data: "rx_supplements=" + a1+ b1+ c1,
			success: function(data){
				if (data == 'Close Chart') {
					window.location = "<?php echo site_url();?>";
				} else {
					$.jGrowl(data);
					$("#orders_supplements_header").hide('fast');
					$("#supplement_text").val('');
					$("#supplement_inactivate_text").val('');
					$("#supplement_reactivate_text").val('');
					$("#supplements_list_dialog").dialog('close');
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('billing/encounters/check_orders');?>",
						dataType: "json",
						success: function(data){
							$('#button_orders_labs_status').html(data.labs_status);
							$('#button_orders_rad_status').html(data.rad_status);
							$('#button_orders_cp_status').html(data.cp_status);
							$('#button_orders_ref_status').html(data.ref_status);
							$('#button_orders_rx_status').html(data.rx_status);
							$('#button_orders_imm_status').html(data.imm_status);
							$('#button_orders_sup_status').html(data.sup_status);
						}
					});
				}
			}
		});
	});
	$("#cancel_orders_supplements_helper").click(function() {
		$("#orders_supplements_header").hide('fast');
		$("#supplement_text").val('');
		$("#supplement_inactivate_text").val('');
		$("#supplement_reactivate_text").val('');
		$('#edit_sup_form').clearForm();
		$('#edit_sup_form').hide('fast');
		$("#supplements_list_dialog").dialog('close');
	});
	$('#save_oh_supplements').click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('billing/encounters/oh_save1/supplements');?>",
			success: function(data){
				$.jGrowl(data);
				$("#oh_supplements_header").hide('fast');
				$("#supplements_list_dialog").dialog('close');
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/encounters/check_oh');?>",
					dataType: "json",
					success: function(data){
						$('#button_oh_sh_status').html(data.sh_status);
						$('#button_oh_etoh_status').html(data.etoh_status);
						$('#button_oh_tobacco_status').html(data.tobacco_status);
						$('#button_oh_drugs_status').html(data.drugs_status);
						$('#button_oh_employment_status').html(data.employment_status);
						$('#button_oh_meds_status').html(data.meds_status);
						$('#button_oh_supplements_status').html(data.supplements_status);
						$('#button_oh_allergies_status').html(data.allergies_status);
					}
				});
			}
		});
	});
</script>
