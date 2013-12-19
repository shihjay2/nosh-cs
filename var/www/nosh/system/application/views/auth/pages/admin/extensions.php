<script type="text/javascript">
$(document).ready(function() {
	$.ajax({
		url: "<?php echo site_url('admin/setup/check_admin');?>",
		type: "POST",
		success: function(data){
			if (data != "Yes") {
				$(".practice_exclude").hide();
			}
		}
	});
	$("#extensions_accordion").accordion({active: false, fillSpace: true, heightStyle: "content"});
	$("#save_extensions_tab").click(function(){
		var str = $("#extensions_form").serialize();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/edit_extensions/');?>",
			data: str,
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#save_extensions_taba").click(function(){
		var str = $("#extensions_form").serialize();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/edit_extensions/');?>",
			data: str,
			success: function(data){
				$.jGrowl(data);
				var active = $('#dashboard_admin_tabs').tabs("option","active");
				var newactive = active + 1;
				$('#dashboard_admin_tabs').tabs('option', 'active', newactive);
			}
		});
	});
	$("#cancel_extensions_tab").click(function(){
		window.location="<?php echo site_url('start');?>";
	});
	$("#updox_extension").addOption({"n":"No","y":"Yes"});
	$("#rcopia_extension").addOption({"n":"No","y":"Yes"});
	$("#snomed_extension").addOption({"n":"No","y":"Yes"});
	$("#mtm_extension").addOption({"n":"No","y":"Yes"});
	$("#extensions_form select").val("n");
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('admin/setup/get_providers/');?>",
		dataType: "json",
		success: function(data){
			if (data.message == "OK") {
				$("#mtm_alert_users").addOption(data, false).removeOption("message").trigger("liszt:updated");
			} else {
				$.jGrowl(data.message);
			}
		}
	});
	$("#mtm_alert_users").chosen();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('admin/setup/get_extensions/');?>",
		dataType: "json",
		success: function(data){
			$.each(data, function(key, value){
				if (key == "mtm_alert_users") {
					var users_array = String(value).split(",");
					var length = users_array.length;
					for (var i = 0; i < length; i++) {
						$("#mtm_alert_users").selectOptions(users_array[i]);
					}
				} else {
					$('#' + key).val(value);
				}
			});
			$("#mtm_alert_users").trigger("liszt:updated");
		}
	});
	$("#save_extensions_tab").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#save_extensions_taba").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$("#cancel_extensions_tab").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#snomed_extension").change(function(){
		if ($(this).val() == 'y') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/check_extension/snomed');?>",
				success: function(data){
					if (data == "Extension status: OK!") {
						$.jGrowl(data);
					} else {
						$.jGrowl(data, { sticky: true });
						$("#snomed_extension").val("n");
					}
				}
			});
		}
	});
});
</script>
<form id="extensions_form">
	<button type="button" id="save_extensions_tab">Save</button>
	<button type="button" id="save_extensions_taba">Save and Continue</button>
	<button type="button" id="cancel_extensions_tab">Cancel</button>
	<hr  class="ui-state-default"/>
	<div id="extensions_accordion">
		<h3>DrFirst Rcopia Integration</h3>
		<div>
			Enable DrFirst Rcopia Extension:<br>
			<select name="rcopia_extension" id="rcopia_extension" class="text ui-widget-content ui-corner-all"></select><br><br>
			DrFirst Rcopia Vendor Username for the Practice (apiVendor):<br>
			<input type="text" name="rcopia_apiVendor" id="rcopia_apiVendor" class="text ui-widget-content ui-corner-all" size="42"/><br><br>
			DrFirst Rcopia Vendor Password for the Practice (apiPass):<br>
			<input type="text" name="rcopia_apiPass" id="rcopia_apiPass" class="text ui-widget-content ui-corner-all" size="42" value=""/><br><br>
			DrFirst Rcopia Practice Username (apiPractice):<br>
			<input type="text" name="rcopia_apiPractice" id="rcopia_apiPractice" class="text ui-widget-content ui-corner-all" size="42" value=""/><br><br>
			DrFirst Rcopia Vendor Name (apiSystem):<br>
			<input type="text" name="rcopia_apiSystem" id="rcopia_apiSystem" class="text ui-widget-content ui-corner-all" size="42" value=""/>
		</div>
		<h3>Updox Sync Integration</h3>
		<div>
			Enable Updox Sync Extension:<br>
			<select name="updox_extension" id="updox_extension" class="text ui-widget-content ui-corner-all"></select>
		</div>
		<h3>Vivacare Patient Education Materials</h3>
		<div>
			Username for Vivacare (XXXXXX in http://www.XXXXXX.fromyourdoctor.com when you registered):<br>
			<input type="text" name="vivacare" id="vivacare" class="text ui-widget-content ui-corner-all" size="42" value=""/><br><br>
			<a href="https://vivacare.com/provider/register/register.jsp" target="_blank">Register at Vivacare for free.</a>
		</div>
		<h3 class="practice_exclude">SNOMED-CT</h3>
		<div class="practice_exclude">
			Enable SNOMED-CT Extension:<br>
			<select name="snomed_extension" id="snomed_extension" class="text ui-widget-content ui-corner-all"></select>
		</div>
		<h3>Medicare Medication Therapy Management (MTM) Integration</h3>
		<div>
			Enable Medicare Medication Therapy Management (MTM) Extension:<br>
			<select name="mtm_extension" id="mtm_extension" class="text ui-widget-content ui-corner-all"></select><br><br>
			Medication Therapy Management (MTM) Extension Alert Providers:</td>
			<select name="mtm_alert_users[]" id="mtm_alert_users" multiple="multiple" style="width:400px" class="text ui-widget-content ui-corner-all"></select>
		</div>
		<h3>PeaceHealth Laboratories</h3>
		<div>
			Practice ID number:<br>
			<input type="text" name="peacehealth_id" id="peacehealth_id" class="text ui-widget-content ui-corner-all" size="42" value=""/>
		</div>
	</div>
</form>
