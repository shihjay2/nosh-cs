<script type="text/javascript">
$(function() {
	$("#dashboard_admin_tabs").tabs();
});
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
	$("#save_admin_tab2").click(function(){
		var dir1 = $("#documents_dir");
		var bValid = true;
		bValid = bValid && checkEmpty(dir1, "Documents Directory");
		if (bValid) {
			var dir = $("#documents_dir").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/checkdir/');?>",
				data: "documents_dir=" + dir,
				success: function(data){
					if (data == 'You need to set the folder to writable permissions.') {
						$.jGrowl(data);
					} else {
						var str = $("#admin_form_2").serialize();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('admin/setup/practiceinfo/');?>",
							data: str,
							success: function(data){
								$.jGrowl(data);
							}
						});
					}
				}
			});
		}
	});
	$("#save_admin_tab2a").click(function(){
		var dir1 = $("#documents_dir");
		var bValid = true;
		bValid = bValid && checkEmpty(dir1, "Documents Directory");
		if (bValid) {
			var dir = $("#documents_dir").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/setup/checkdir/');?>",
				data: "documents_dir=" + dir,
				success: function(data){
					if (data == 'You need to set the folder to writable permissions.') {
						$.jGrowl(data);
					} else {
						var str = $("#admin_form_2").serialize();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('admin/setup/practiceinfo/');?>",
							data: str,
							success: function(data){
								$.jGrowl(data);
								var active = $('#dashboard_admin_tabs').tabs("option","active");
								var newactive = active + 1;
								$('#dashboard_admin_tabs').tabs('option', 'active', newactive);
							}
						});
					}
				}
			});
		}
	});
	$("#cancel_admin_tab2").click(function(){
		window.location="<?php echo site_url('start');?>";
	});
	$("#npi").mask("9999999999");
	$("#tax_id").mask("99-9999999");
	$("#default_pos_id").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/pos');?>",
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
			$(this).end().val(ui.item.value);
		}
	});
	$("#save_admin_tab2").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#save_admin_tab2a").button({
		icons: {
			primary: "ui-icon-check"
		},
	});
	$("#cancel_admin_tab2").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
});
</script>
<form id="admin_form_2">
<button type="button" id="save_admin_tab2">Save</button>
<button type="button" id="save_admin_tab2a">Save and Continue</button>
<button type="button" id="cancel_admin_tab2">Cancel</button>
<hr  class="ui-state-default"/>
<table>
	<tr>
		<td>Practice Primary Contact:</td>
		<td><input type="text" name="primary_contact" id="primary_contact" class="text ui-widget-content ui-corner-all" size="42" value="<?php if ($admin->primary_contact != '') {echo $admin->primary_contact;}?>"/></td>
	</tr>
	<tr>
		<td>Practice NPI:</td>
		<td><input type="text" name="npi" id="npi" class="text ui-widget-content ui-corner-all" size="42" value="<?php if ($admin->npi != '') {echo $admin->npi;}?>"/></td>
	</tr>
	<tr>
		<td>Practice Medicare Number:</td>
		<td><input type="text" name="medicare" id="medicare" class="text ui-widget-content ui-corner-all" size="42" value="<?php if ($admin->medicare != '') {echo $admin->medicare;}?>"/></td>
	</tr>
	<tr>
		<td>Practice Tax ID:</td>
		<td><input type="text" name="tax_id" id="tax_id" class="text ui-widget-content ui-corner-all" size="42" value="<?php if ($admin->tax_id != '') {echo $admin->tax_id;}?>"/></td>
	</tr>
	<tr>
		<td>Default Practice Location:</td>
		<td><input type="text" name="default_pos_id" id="default_pos_id" class="text ui-widget-content ui-corner-all" size="42" value="<?php if ($admin->default_pos_id != '') {echo $admin->default_pos_id;}?>"/></td>
	</tr>
	<tr class="practice_exclude">
		<td>Documents Directory:</td>
		<td><input type="text" name="documents_dir" id="documents_dir" class="text ui-widget-content ui-corner-all" size="42" value="<?php if ($admin->documents_dir != '') {echo $admin->documents_dir;}?>"/></td>
	</tr>
	<tr>
		<td>Weight Unit:</td>
		<td>
			<select name="weight_unit" id="weight_unit" class="select ui-widget-content ui-corner-all">
				<option value="lbs" <?php echo set_select('weight_unit', 'lbs', TRUE); ?>>Pounds</option>
				<option value="kg" <?php echo set_select('weight_unit', 'kg'); ?>>Kilograms</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Height Unit:</td>
		<td>
			<select name="height_unit" id="height_unit" class="text ui-widget-content ui-corner-all">
				<option value="in" <?php echo set_select('height_unit', 'in', TRUE); ?>>Inches</option>
				<option value="cm" <?php echo set_select('height_unit', 'cm'); ?>>Centimeters</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Temperature Unit:</td>
		<td>
			<select name="temp_unit" id="temp_unit" class="text ui-widget-content ui-corner-all">
				<option value="F" <?php echo set_select('temp_unit', 'F', TRUE); ?>>Fahrenheit</option>
				<option value="C" <?php echo set_select('temp_unit', 'C'); ?>>Celsius</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Head Circumerence Unit:</td>
		<td>
			<select name="hc_unit" id="hc_unit" class="text ui-widget-content ui-corner-all">
				<option value="in" <?php echo set_select('hc_unit', 'in'); ?>>Inches</option>
				<option value="cm" <?php echo set_select('hc_unit', 'cm', TRUE); ?>>Centimeters</option>
			</select>
		</td>
	</tr>
</table>
</form>
