<table width="100%" cellspacing="10">
	<tr>
		<th style="background-color: gray;color: #FFFFFF; text-align: left;">ENCOUNTER DETAILS</th>
	</tr>
	<tr>
		<td valign="top">
			<h3>Encounter <?php echo $eid;?></h3>
			Date of Service: <?php echo $encounter_DOS;?><br>
			Age at Date of Service: <?php echo $age1;?><br>
			Provider: <?php echo $encounter_provider;?><br>
		</td>
	</tr>
	<tr><td valign="top"><h3>Chief Complaint: </h3><p><?php echo $encounter_cc;?></p></td></tr>
	<tr><td valign="top"><?php echo $hpi;?></td></tr>
	<tr><td valign="top"><?php echo $ros;?></td></tr>
	<tr><td valign="top"><?php echo $oh;?></td></tr>
	<tr><td valign="top"><?php echo $vitals;?></td></tr>
	<tr><td valign="top"><?php echo $pe;?></td></tr>
	<tr><td valign="top"><?php echo $labs;?></td></tr>
	<tr><td valign="top"><?php echo $procedure;?></td></tr>
	<tr><td valign="top"><?php echo $assessment;?></td></tr>
	<tr><td valign="top"><?php echo $orders;?></td></tr>
	<tr><td valign="top"><?php echo $rx;?></td></tr>
	<tr><td valign="top"><?php echo $plan;?></td></tr>
	<tr><td valign="top"><hr /><?php echo $billing;?><br />Electronically signed by <?php echo $encounter_provider;?> on <?php echo $date_signed;?>.</td></tr>

</table><br>
