<html>
<head>
<title>NOSH ChartingSystem Installer</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/cupertino/jquery-ui.css" rel="Stylesheet" />
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link type="text/css" href="<?php echo base_url().'css/main.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/jquery.jgrowl.css';?>" rel="Stylesheet" />

<style type="text/css">
h1 {
	text-align: center;
}
</style>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.maskedinput.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.jgrowl.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.selectboxes.js';?>"></script>
<script type="text/javascript" >
$(document).ready(function() {
	$("#phone").mask("(999) 999-9999");
	$("#fax").mask("(999) 999-9999");
	var directory = '/noshdocuments/';
	$("#documents_dir").val(directory);
	var states = {"AL":"Alabama","AK":"Alaska","AS":"America Samoa","AZ":"Arizona","AR":"Arkansas","CA":"California","CO":"Colorado","CT":"Connecticut","DE":"Delaware","DC":"District of Columbia","FM":"Federated States of Micronesia","FL":"Florida","GA":"Georgia","GU":"Guam","HI":"Hawaii","ID":"Idaho","IL":"Illinois","IN":"Indiana","IA":"Iowa","KS":"Kansas","KY":"Kentucky","LA":"Louisiana","ME":"Maine","MH":"Marshall Islands","MD":"Maryland","MA":"Massachusetts","MI":"Michigan","MN":"Minnesota","MS":"Mississippi","MO":"Missouri","MT":"Montana","NE":"Nebraska","NV":"Nevada","NH":"New Hampshire","NJ":"New Jersey","NM":"New Mexico","NY":"New York","NC":"North Carolina","ND":"North Dakota","OH":"Ohio","OK":"Oklahoma","OR":"Oregon","PW":"Palau","PA":"Pennsylvania","PR":"Puerto Rico","RI":"Rhode Island","SC":"South Carolina","SD":"South Dakota","TN":"Tennessee","TX":"Texas","UT":"Utah","VT":"Vermont","VI":"Virgin Island","VA":"Virginia","WA":"Washington","WV":"West Virginia","WI":"Wisconsin","WY":"Wyoming"};
	$("#state").addOption(states, false);
	$("#password").focus();
	$("#submit_button").button();
});
</script>
</head>
<body>
<div id="logo">Nosh</div><br>
<div id="mainborder_full" class="ui-corner-all ui-tabs ui-widget ui-widget-content">
	<div id="installcontent">
		<h3>Welcome to the NOSH ChartingSystem Installation</h3>
		<?php echo form_open('install', array('id'=>'install'));?>
		<p style="font-size:13px;">Please fill out the entries to complete the installation of NOSH ChartingSystem.</p>
		<p style="font-size:13px;">This database tables will be installed onto the MYSQL database 'nosh'.</p>
		<p style="font-size:13px;">You will need to establish a Google Gmail account to be able to send e-mail from the system for patient appointment reminders, non-Protected Health Information messages, and faxes.</p>
		<p style="color: #C00;font-size:13px"><strong><?php echo validation_errors(); ?></strong></p>
		<table class="form" style="font-size:13px;">
			<tr>
				<td><?php echo form_label('Administrator Username:', 'username');?></td>
				<td><?php echo form_input(array('name'=>'username','id'=>'username','value'=>'admin','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Password:', 'password');?></td>
				<td><?php echo form_password(array('name'=>'password','id'=>'password','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Password Confirm:', 'conf_password');?></td>
				<td><?php echo form_password(array('name'=>'conf_password','id'=>'conf_password','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Email:', 'email');?></td>
				<td><?php echo form_input(array('name'=>'email','id'=>'email','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Practice Name:', 'practice_name');?></td>
				<td><?php echo form_input(array('name'=>'practice_name','id'=>'practice_name','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Street Address:', 'street_address1');?></td>
				<td><?php echo form_input(array('name'=>'street_address1','id'=>'street_address1','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Street Address Line 2:', 'street_address2');?></td>
				<td><?php echo form_input(array('name'=>'street_address2','id'=>'street_address2','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('City:', 'city');?></td>
				<td><?php echo form_input(array('name'=>'city','id'=>'city','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('State:', 'state');?></td>
				<td><select name="state" id="state" class="text ui-widget-content ui-corner-all"></select><td>
			</tr>
			<tr>
				<td><?php echo form_label('Zip:', 'zip');?></td>
				<td><?php echo form_input(array('name'=>'zip','id'=>'zip','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Phone:', 'phone');?></td>
				<td><?php echo form_input(array('name'=>'phone','id'=>'phone','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Fax:', 'fax');?></td>
				<td><?php echo form_input(array('name'=>'fax','id'=>'fax','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('System Directory for Patient Documents (format /dir1/dir2/dir3/)', 'documents_dir');?></td>
				<td><?php echo form_input(array('name'=>'documents_dir','id'=>'documents_dir','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('MySQL Username:', 'db_username');?></td>
				<td><?php echo form_input(array('name'=>'db_username','id'=>'db_username','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('MySQL Password:', 'db_password');?></td>
				<td><?php echo form_password(array('name'=>'db_password','id'=>'db_password','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Gmail Username for Sending E-mail:', 'smtp_user');?></td>
				<td><?php echo form_input(array('name'=>'smtp_user','id'=>'smtp_user','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
			<tr>
				<td><?php echo form_label('Gmail Password for Sending E-mail:', 'smtp_pass');?></td>
				<td><?php echo form_password(array('name'=>'smtp_pass','id'=>'smtp_pass','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
			</tr>
		
		</table><br>
		<?php echo form_submit(array('name'=>'mysubmit','id'=>'submit_button','value'=>'Install NOSH ChartingSystem'));?>
		<?php echo form_close();?>
	</div>
</div>
</body>
</html>
