<script type="text/javascript">
$(document).ready(function() {
	$('#provider_list2').change(function() {
		var id = $('#provider_list2').val();
		if(id){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/schedule/set_provider/');?>",
				data: "id=" + id,
				success: function(data){
					$("#provider_schedule1").load("<?php echo site_url("admin/schedule/schedule_view");?>");
				}
			});
		} 
	});
	$('#provider_list2').val('<?php if ($provider_id != '') {echo $provider_id;}?>');
});
</script>

<form id="admin_schedule_form_4" />
Provider: <select id ="provider_list2" name="provider_list2" class="text ui-widget-content ui-corner-all"><?php if ($providers != '') {echo $providers;}?></select><br>
<hr />
<div id="provider_schedule1" style="overflow:auto;"></div>

