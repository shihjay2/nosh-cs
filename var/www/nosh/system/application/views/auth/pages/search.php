<?php if(user_group('admin') OR user_group('provider') OR user_group('assistant') OR user_group('billing')) {?>
<script type="text/javascript">
	$("#searchpt").focus();
	$("#searchpt").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
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
		minLength: 1,
		select: function(event, ui){
			$("#hidden_pid").val(ui.item.id);
			var oldpt = '<?php echo $this->session->userdata("pid");?>';
			if(!oldpt){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('search/openchart/');?>",
					data: "pid=" + ui.item.id,
					success: function(data){
						window.location = "<?php echo site_url('search/openchart1/');?>";
					}
				});
			} else {
				if(ui.item.id != oldpt){
					$("#search_dialog").dialog('open');
				} else {
					$.jGrowl("Patient chart already open!");
				}
			}
		}
	});
	var gender = {"m":"Male","f":"Female"};
	$("#gender").addOption(gender, false);
	$("#DOB").mask("99/99/9999").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$("#search_dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		resizable: true,
		height:200,
		width:350,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'OK': function() {
				$("#search_dialog").dialog('close');
				var pid = $("#hidden_pid").val();
				var eid = $("#hidden_eid").val();
				if(pid){
					if(eid){
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('search/openchart/');?>",
							data: "pid=" + pid,
							success: function(data){
								var eid = $("#hidden_eid").val();
								$.ajax({
									type: "POST",
									url: "<?php echo site_url('provider/chartmenu/encounter_id_set/');?>",
									data: "eid=" + eid,
									success: function(data) {
										$("#hidden_eid").val('');
										window.location = "<?php echo site_url ('provider/encounters/view/');?>";
									}
								});
							}
						});
					} else {
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('search/openchart/');?>",
							data: "pid=" + pid,
							success: function(data){
								$("#hidden_pid").val('');
								window.location = "<?php echo site_url('search/openchart1/');?>";
							}
						});
					}
				} else {
					$.jGrowl("Please enter patient to open chart!");
				}
			},
			Cancel: function() {
				$("#search_dialog").dialog('close');
			}
		}
	});
	$("#openNewPatient").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		$("#new_patient").dialog('open');
		$("#lastname").focus();
	});
	$("#new_patient").dialog({
		bgiframe: true,
		autoOpen: false,
		resizable: true,
		height:400,
		width:550,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'Add Only': function() {
				var str = $("#new_patient_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/newpatient/');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#new_patient").dialog('close');
							$("#new_patient_form").clearForm();
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			'Add and Open Chart': function() {
				var str = $("#new_patient_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('search/newpatient1/');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							$("#new_patient").dialog('close');
							$("#new_patient_form").clearForm();
							window.location = "<?php echo site_url('search/openchart1/');?>";
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			},
			Cancel: function() {
				$("#new_patient").dialog('close');
			}
		}
	});
</script>
<div id ="searchbar">
	<div id="logo" class="innersearchbar" style="line-height:67px;width:100px;">Nosh</div>
	<div class="innersearchbar" style="line-height:67px;width:445px;">
		<input type="text" name="searchpt" id="searchpt" style="width:295px" class="text ui-widget-content ui-corner-all" placeholder="Search patient and then select to open the chart."/>
		<input type="hidden" name="hidden_pid" id="hidden_pid">
		<input type="hidden" name="hidden_eid" id="hidden_eid">
		<button type="button" id="openNewPatient" style="font-size:12px;">New Patient</button>&nbsp
	</div>
	<div class="innersearchbar" style="width:300px;">
		<?php if ($pt != '' && $user != '') {echo anchor($user . '/chartmenu/', '[Active Patient Chart: ' . $pt . ']');}?>
		<?php if ($encounter != '' && $user != '' && $user != 'billing') {echo '<br>' . anchor($user . '/encounters/view/', '[Active Encounter #: ' . $encounter . ']');}?>
	</div>
	<div class="innersearchbar" style="float:right;line-height:67px;width:110px;">
		<?php if ($pt != '' && $user != '') {echo "<img src='" . base_url()."images/cancel.png' border='0' height='30' width='30' style='vertical-align:middle'>";}?>
		<?php if ($pt != '' && $user != '') {echo anchor('search/closechart', 'Close Chart');}?>
	</div>
</div>

<div id="search_dialog" title="Confirmation">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0"></span>Opening a new chart requires closing the chart for <?php echo $this->session->userdata('ptname');?>. Are you sure?</p>
</div>
<div id="new_patient" title="Add New Patient">
	<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>All fields are required.<br>
	Add and Open Chart command will close any existing open charts!<br>
	<br>
	<form name="new_patient_form" id="new_patient_form">
		<table>
			<tr>
				<td><label for="lastname">Last Name</label></td>
				<td><input type="text" name="lastname" id="lastname" size="42" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="firstname">First Name</label></td>
				<td><input type="text" name="firstname" id="firstname" size="42" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="DOB">Date of Birth</label></td>
				<td><input type="text" name="DOB" id="DOB" class="text ui-widget-content ui-corner-all" /></td>
			</tr>
			<tr>
				<td><label for="gender">Gender</label></td>
				<td><select name="gender" id="gender" class="text ui-widget-content ui-corner-all"></select></td>
			</tr>
		</table>
	</form>
</div>
<?php } else {?>
<div id ="searchbar">
	<table>
	<tr><td><div id="logo1">Nosh</div></td>
	</tr></table>
</div>
<?php }?>
