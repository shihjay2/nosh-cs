<table class="top" cellspacing="10">
	<tr>
		<th style="width:350">PATIENT DEMOGRAPHICS</th>
		<th style="width:350">GUARANTOR AND INSURANCE INFORMATION</th>
	</tr>
	<tr>
		<td>
			<?php echo $patientInfo->lastname. ', ' . $patientInfo->firstname;?><br>
			Date of Birth: <?php echo $dob;?><br>
			<?php echo $patientInfo->address;?><br>
			<?php echo $patientInfo->city . ', ' . $patientInfo->state . ' ' . $patientInfo->zip;?><br>
			<?php echo $patientInfo->phone_home;?><br>
		</td>
		<td>
			<?php echo $insuranceInfo;?>
		</td>
	</tr>
</table><br>
<hr />
<?php echo $letter;?>
</body>
</html>
