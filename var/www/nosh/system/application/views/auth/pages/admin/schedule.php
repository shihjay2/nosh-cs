<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load("<?php echo site_url("search/loadpage");?>");
	$('.schedule_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 15
	});
	var weekends = '<?php if ($schedule->weekends != '') {echo $schedule->weekends;}?>';
	if (weekends == 'Yes') {
		$("#weekends").attr('checked', true);
	} else {
		$("#weekends").attr('checked', false);
	}
	
	$("#sun_all").click(function() {
		if ($("#sun_all").is(":checked")) {
			$("#sun_o").val("");
			$("#sun_c").val("");
		}
	});
	
	$("#mon_all").click(function() {
		if ($("#mon_all").is(":checked")) {
			$("#mon_o").val("");
			$("#mon_c").val("");
		}
	});
	
	$("#tue_all").click(function() {
		if ($("#tue_all").is(":checked")) {
			$("#tue_o").val("");
			$("#tue_c").val("");
		}
	});
	
	$("#wed_all").click(function() {
		if ($("#wed_all").is(":checked")) {
			$("#wed_o").val("");
			$("#wed_c").val("");
		}
	});
	
	$("#thu_all").click(function() {
		if ($("#thu_all").is(":checked")) {
			$("#thu_o").val("");
			$("#thu_c").val("");
		}
	});
	
	$("#fri_all").click(function() {
		if ($("#fri_all").is(":checked")) {
			$("#fri_o").val("");
			$("#fri_c").val("");
		}
	});
	
	$("#sat_all").click(function() {
		if ($("#sat_all").is(":checked")) {
			$("#sat_o").val("");
			$("#sat_c").val("");
		}
	});
	
	$("#save_admin_schedule_tab1").click(function(){
		var str = $("#admin_schedule_form_1").serialize();		
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/schedule/global_settings/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#save_admin_schedule_tab1a").click(function(){
		var str = $("#admin_schedule_form_1").serialize();		
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/schedule/global_settings/');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					$('#schedule_admin_tabs').tabs('select', 1);
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_admin_schedule_tab1").click(function(){
		window.location="<?php echo site_url('start');?>";
	});
});
</script>
<div id="heading2"></div>
<div id ="mainborder_full" class="ui-corner-all">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Scheduling</h4>
		<div id="noshtabs">
			<div id="schedule_admin_tabs">
				<ul>
					<li><a href="#admin_schedule_tabs_1">Entire Clinic Schedule</a></li>
					<li><?php echo anchor('admin/schedule/visit_types/', 'Visit Types');?></li>
					<li><?php echo anchor('admin/schedule/exceptions/', 'Provider Schedule Exceptions');?></li>
					<li><?php echo anchor('admin/schedule/provider/', 'Provider Schedule');?></li>
				</ul>
				<div id="admin_schedule_tabs_1">
					<form id="admin_schedule_form_1" />
					<input type="button" id="save_admin_schedule_tab1" value="Save" class="ui-button ui-state-default ui-corner-all">
					<input type="button" id="save_admin_schedule_tab1a" value="Save and Continue" class="ui-button ui-state-default ui-corner-all">
					<input type="button" id="cancel_admin_schedule_tab1" value="Cancel" class="ui-button ui-state-default ui-corner-all">
					<hr class="ui-state-default"/>
					<table>
						<tr>
							<td>Include Weekends in the schedule:</td>
							<td><input type="checkbox" name="weekends" id="weekends" value="Yes" class="text ui-widget-content ui-corner-all"/></td>
						</tr>
						<tr>
							<td>First hour/time that will be displayed on the schedule:</td>
							<td><input type="text" name="minTime" id="minTime" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->minTime != '') {echo $schedule->minTime;}?>"/></td>
						</tr>
						<tr>
							<td>Last hour/time that will be displayed on the schedule:</td>
							<td><input type="text" name="maxTime" id="maxTime" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->maxTime != '') {echo $schedule->maxTime;}?>"/></td>
						</tr>
					</table>
					<br>
					<strong>Clinic-wide operation hours</strong>
					<table id="global_schedule" class="ui-widget ui-widget-content ui-corner-all">
						<thead>
							<tr class="ui-widget-header ui-corner-all">
								<th>Day</th>
								<th>Open</th>
								<th>Close</th>
								<th>Closed All Day</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Sunday</td>
								<td><input type="text" name="sun_o" id="sun_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->sun_o != '') {echo $schedule->sun_o;}?>"/></td>
								<td><input type="text" name="sun_c" id="sun_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->sun_c != '') {echo $schedule->sun_c;}?>"/></td>
								<td><input type="checkbox" id="sun_all" name="sun_all"/></td>
							</tr>
							<tr>
								<td>Monday</td>
								<td><input type="text" name="mon_o" id="mon_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->mon_o != '') {echo $schedule->mon_o;}?>"/></td>
								<td><input type="text" name="mon_c" id="mon_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->mon_c != '') {echo $schedule->mon_c;}?>"/></td>
								<td><input type="checkbox" id="mon_all" name="mon_all"/></td>
							</tr>
							<tr>
								<td>Tuesday</td>
								<td><input type="text" name="tue_o" id="tue_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->tue_o != '') {echo $schedule->tue_o;}?>"/></td>
								<td><input type="text" name="tue_c" id="tue_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->tue_c != '') {echo $schedule->tue_c;}?>"/></td>
								<td><input type="checkbox" id="tue_all" name="tue_all"/></td>
							</tr>
							<tr>
								<td>Wednesday</td>
								<td><input type="text" name="wed_o" id="wed_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->wed_o != '') {echo $schedule->wed_o;}?>"/></td>
								<td><input type="text" name="wed_c" id="wed_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->wed_c != '') {echo $schedule->wed_c;}?>"/></td>
								<td><input type="checkbox" id="wed_all" name="wed_all"/></td>
							</tr>
							<tr>
								<td>Thursday</td>
								<td><input type="text" name="thu_o" id="thu_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->thu_o != '') {echo $schedule->thu_o;}?>"/></td>
								<td><input type="text" name="thu_c" id="thu_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->thu_c != '') {echo $schedule->thu_c;}?>"/></td>
								<td><input type="checkbox" id="thu_all" name="thu_all"/></td>
							</tr>
							<tr>
								<td>Friday</td>
								<td><input type="text" name="fri_o" id="fri_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->fri_o != '') {echo $schedule->fri_o;}?>"/></td>
								<td><input type="text" name="fri_c" id="fri_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->fri_c != '') {echo $schedule->fri_c;}?>"/></td>
								<td><input type="checkbox" id="fri_all" name="fri_all"/></td>
							</tr>
							<tr>
								<td>Saturday</td>
								<td><input type="text" name="sat_o" id="sat_o" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->sat_o != '') {echo $schedule->sat_o;}?>"/></td>
								<td><input type="text" name="sat_c" id="sat_c" size="20" class="schedule_time text ui-widget-content ui-corner-all" value="<?php if ($schedule->sat_c != '') {echo $schedule->sat_c;}?>"/></td>
								<td><input type="checkbox" id="sat_all" name="sat_all"/></td>
							</tr>
						</tbody>
					</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
