<hr class="ui-state-default"/><h4>Previous versions:</h4>
<div id="previous_versions"></div>
<hr class="ui-state-default"/>
<div id="encounter_text">
	<table style="width:700" cellspacing="10">
		<tr>
			<th style="width:350">PATIENT INFORMATION</th>
			<th style="width:350">ENCOUNTER INFORMATION</th>
		</tr>
		<tr>
			<td valign="top">
				<h4><?php echo $patientInfo->lastname. ', ' . $patientInfo->firstname;?></h4>
				Date of Birth: <?php echo $dob;?><br>
				Current Age: <?php echo $age;?><br>
				Age at Date of Service: <?php echo $age1;?><br>
				Gender: <?php echo $gender;?><br>
			</td>
			<td valign="top">
				<h4>Encounter <?php echo $eid;?></h4>
				Date of Service: <?php echo $encounter_DOS;?><br>
				Provider: <?php echo $encounter_provider;?><br>
				Status: <?php echo $status;?><br>
			</td>
		</tr>
	</table><br>
	<hr class="ui-state-default"/>

	<h4>Chief Complaint:</h4>
	<p class="view"><?php echo $encounter_cc;?></p>
	<?php echo $hpi;?>
	<?php echo $ros;?>
	<?php echo $oh;?>
	<?php echo $vitals;?>
	<?php echo $pe;?>
	<?php echo $labs;?>
	<?php echo $procedure;?>
	<?php echo $assessment;?>
	<?php echo $orders;?>
	<?php echo $rx;?>
	<?php echo $plan;?>
	<br />	
	<hr class="ui-state-default"/>
	<?php echo $billing;?>
</div>
<script type="text/javascript">
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('billing/encounters/previous_versions/') . '/' . $eid;?>",
		success: function(data) {
			$('#previous_versions').html(data);
			$('.addendum_class').click(function(){
				var eid = $(this).attr("id");
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('billing/encounters/get_previous_versions/');?>" + "/" + eid,
					success: function(data) {
						$('#encounter_text').html(data);
					}
				});
			});
		}
	});
	
</script>
