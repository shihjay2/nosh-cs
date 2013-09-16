<script type="text/javascript">
$(document).ready(function() {
	$("#heading2").load('<?php echo site_url("search/loadpage");?>');
	$(document).idleTimeout({
		inactivity: 3600000,
		noconfirm: 10000,
		alive_url: '<?php echo site_url("patient/schedule");?>',
		redirect_url: '<?php echo site_url("start");?>',
		logout_url: '<?php echo site_url("logout");?>',
		sessionAlive: 300000
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('patient/schedule/next_appointment/');?>",
		success: function(data){
			$("#next_appointment").html(data);
		}
	});
	$('#provider_list2').change(function() {
		var id = $('#provider_list2').val();
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/schedule/set_provider/');?>",
				data: "id=" + id,
				success: function(data){
					$("#provider_schedule1").load('<?php echo site_url("patient/schedule/schedule_view");?>');
				}
			});
		} 
	});
	$('#provider_list2').val('<?php if ($provider_id != '') {echo $provider_id;}?>');
});
</script>
<div id="heading2"></div>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="maincontent_full">
		<h4>NOSH ChartingSystem Appointment Scheduling</h4>
		<hr class="ui-state-default"/>
		<div id="next_appointment"></div><br>
		Step 1: Choose a provider to schedule an appointment: <select id ="provider_list2" name="provider_list2" class="text ui-widget-content ui-corner-all"><?php if ($providers != '') {echo $providers;}?></select><br>
		<hr class="ui-state-default"/>
		<div id="provider_schedule1" style="overflow:auto;"></div>
	</div>
</div>

