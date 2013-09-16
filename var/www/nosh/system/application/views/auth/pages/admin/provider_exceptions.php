<script type="text/javascript">
$(document).ready(function() {
	$('#provider_list1').click(function() {
		var provider_id = $('#provider_list1').val();
		if(provider_id){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/schedule/set_provider/');?>",
				data: "id=" + provider_id,
				success: function(data){
					$("#provider_grid").load("<?php echo site_url('admin/schedule/provider_grid');?>");
				}
			});
		}
	});
});
</script>

Provider: <select id ="provider_list1" name="provider_list1"><?php if ($providers != '') {echo $providers;}?></select><br>
<hr />
<div id="provider_grid"></div>
