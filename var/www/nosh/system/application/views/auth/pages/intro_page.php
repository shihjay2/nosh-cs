<html>
	<head>
		<style>
			body {
				font-family: Arial, sans-serif;
				font-size: 11;
			}
			div.surround_div {
				border: 1px solid black;
			}
			b.smallcaps {
				font-variant: small-caps;
			}
			table.top {
				width: 700;
			}
			table.order {
				width: 700;
			}
			th {
				background-color: gray;
				color: #FFFFFF;
			}
		</style>
	</head>
	<body>
		<div style="float:left;width:300px;">
			<b><?php echo $practiceName;?></b><br><?php echo $practiceInfo;?><br><?php echo auto_link(prep_url($website));?>
		</div>
		<div style="float:left;width:380px;">
			<?php echo $practiceLogo;?>
		</div>
		<div style="border-bottom: 1px solid #000000; text-align: center; padding-bottom: 3mm;width:700px;">
			<b class="smallcaps"><?php echo $title;?></b>
		</div>
