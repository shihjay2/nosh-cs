<html>
<head>
<title>NOSH ChartingSystem Database Connection Fixer</title>
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link type="text/css" href="<?php echo base_url().'css/main.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/redmond/jquery-ui-1.10.0.custom.min.css';?>" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url().'css/jquery.jgrowl.css';?>" rel="Stylesheet" />

<style type="text/css">
h1 {
	text-align: center;
}
</style>
<script type="text/javascript" src="<?php echo base_url().'js/jquery-1.9.1.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery-ui-1.10.0.custom.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.maskedinput.min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.jgrowl.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url().'js/jquery.selectboxes.js';?>"></script>
<script type="text/javascript" >
$(document).ready(function() {
	$("#db_username").focus();
});
</script>
</head>
<body>
<div id="logo">Nosh</div><br>
<div id="mainborder_full" class="ui-corner-all">
<div id="installcontent">
	<h3>Welcome to the NOSH ChartingSystem Database Connection Fixer</h3>
	<?php echo form_open('install/fix_db_conn', array('id'=>'install'));?>
	<p>Please fill out the entries to repair the installation of NOSH EMR.</p>
	
	<p style="color: #C00;"><strong><?php echo validation_errors(); ?></strong></p>
	<table class="form">
		<tr>
			<td><?php echo form_label('MySQL Username:', 'db_username');?></td>
			<td><?php echo form_input(array('name'=>'db_username','id'=>'db_username','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
		</tr>
		<tr>
			<td><?php echo form_label('MySQL Password:', 'db_password');?></td>
			<td><?php echo form_password(array('name'=>'db_password','id'=>'db_password','style'=>'width:500px','class'=>'text ui-widget-content ui-corner-all'));?><td>
		</tr>
	</table><br>
	<?php echo form_submit('mysubmit', 'Repair NOSH ChartingSystem');?>
	<?php echo form_close();?>
</div>
</div>
</body>
</html>
