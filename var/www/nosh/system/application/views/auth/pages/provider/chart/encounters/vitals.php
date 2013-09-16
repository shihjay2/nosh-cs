<div id="vitals_fieldset">
	<input type="hidden" name="vitals_weight_old" id="vitals_weight_old"/>
	<input type="hidden" name="vitals_height_old" id="vitals_height_old"/>
	<input type="hidden" name="vitals_BMI_old" id="vitals_BMI_old"/>
	<input type="hidden" name="vitals_temp_old" id="vitals_temp_old"/>
	<input type="hidden" name="vitals_temp_method_old" id="vitals_temp_method_old"/>
	<input type="hidden" name="vitals_bp_systolic_old" id="vitals_bp_systolic_old"/>
	<input type="hidden" name="vitals_bp_diastolic_old" id="vitals_bp_diastolic_old"/>
	<input type="hidden" name="vitals_bp_position_old" id="vitals_bp_position_old"/>
	<input type="hidden" name="vitals_pulse_old" id="vitals_pulse_old"/>
	<input type="hidden" name="vitals_respirations_old" id="vitals_respirations_old"/>
	<input type="hidden" name="vitals_o2_sat_old" id="vitals_o2_sat_old"/>
	<input type="hidden" name="vitals_vitals_other_old" id="vitals_vitals_other_old"/>
	<?php echo $graphs;?>
	<form id="vitals_form">
		<table>
			<tr>
				<td>
					Weight:<br>
					<input type="text" name="weight" id="vitals_weight" style="width:60px" class="text ui-widget-content ui-corner-all"> <?php echo $practiceInfo->weight_unit;?>
				</td>
				<td>
					Height:<br>
					<input type="text" name="height" id="vitals_height" style="width:60px" class="text ui-widget-content ui-corner-all"> <?php echo $practiceInfo->height_unit;?>
				</td>
				<td valign="top">
					BMI:<br>
					<div id="vitals_bmi_display"></div><input type="hidden" name="BMI" id="vitals_BMI">
				</td>
				<?php echo $hc;?>
			</tr>
			<tr>
				<td>
					Temperature:<br>
					<input type="text" name="temp" id="vitals_temp" style="width:60px" class="text ui-widget-content ui-corner-all"> <?php echo $practiceInfo->temp_unit;?> by <select name="temp_method" id="vitals_temp_method" class="text ui-widget-content ui-corner-all"></select>
				</td>
				<td>
					Blood pressure:<br>
					<input type="text" name="bp_systolic" id="vitals_bp_systolic" style="width:60px" class="text ui-widget-content ui-corner-all"> / <input type="text" name="bp_diastolic" id="vitals_bp_diastolic" style="width:60px" class="text ui-widget-content ui-corner-all">, Position: <select name="bp_position" id="vitals_bp_position" class="text ui-widget-content ui-corner-all"></select>
				</td>
			</tr>
			<tr>
				<td>
					Pulse:<br>
					<input type="text" name="pulse" id="vitals_pulse" style="width:60px" class="text ui-widget-content ui-corner-all">
				</td>
				<td>
					Respirations:<br>
					<input type="text" name="respirations" id="vitals_respirations" style="width:60px" class="text ui-widget-content ui-corner-all">
				</td>
				<td>
					Oxygen saturation:<br>
					<input type="text" name="o2_sat" id="vitals_o2_sat" style="width:60px" class="text ui-widget-content ui-corner-all">
				</td>
			</tr>
		</table><br>
		Notes: <input type="text" name="vitals_other" id="vitals_vitals_other" style="width:500px" class="text ui-widget-content ui-corner-all"><br><br>
	</form>
	<br>
	<table id="vitals_list" class="scroll" cellpadding="0" cellspacing="0"></table>
	<div id="vitals_list_pager" class="scroll" style="text-align:center;"></div><br>
</div>
<div id="graph_dialog" title="Growth Chart">
	<div id="container" style="width: 750px; height: 550px; margin: 0 auto"></div>
</div>
<div id="graph_load" title="Loading...">
	<img src="<?php echo base_url().'images/indicator.gif';?>"> Loading graph.
</div>
<script type="text/javascript">
	$("#vitals_temp_method").addOption({"Oral":"Oral","Axillary":"Axillary","Temporal":"Temporal","Rectal":"Rectal"}, false);
	$("#vitals_bp_position").addOption({"Sitting":"Sitting","Standing":"Standing","Supine":"Supinee"}, false);
	jQuery("#vitals_list").jqGrid({
		url:"<?php echo site_url('provider/encounters/vitals_list/');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Date','Weight','Height','HC','BMI','Temp','Systolic BP','Diastolic BP','Pulse','Resp','O2 Sat','Notes'],
		colModel:[
			{name:'eid',index:'eid',width:1,hidden:true},
			{name:'vitals_date',index:'vitals_date',width:80},
			{name:'weight',index:'weight',width:50},
			{name:'height',index:'height',width:50},
			<?php echo $hc2;?>
			{name:'BMI',index:'BMI',width:50},
			{name:'temp',index:'temp',width:50},
			{name:'bp_systolic',index:'bp_systolic',width:100},
			{name:'bp_diastolic',index:'bp_diastolic',width:100},
			{name:'pulse',index:'pulse',width:50},
			{name:'respirations',index:'respirations',width:50},
			{name:'o2_sat',index:'o2_sat',width:50},
			{name:'vitals_other',index:'vitals_other',width:150}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#vitals_list_pager'),
		sortname: 'eid',
	 	viewrecords: true,
	 	sortorder: "desc",
	 	caption:"Past Vital Signs",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#demographics_insurance_inactive_pager',{search:false,edit:false,add:false,del:false});
	$("#vitals_height").blur(function(){
		var w = $("#vitals_weight").val();
		var h = $("#vitals_height").val();
		if (w != '') {
			if ((w >= 500) || (h >= 120)) {
				alert("Invalid data.  Please check and re-enter!");
			} else {
				var bmi = (Math.round((w * 703) / (h * h)));
				$("#vitals_BMI").val(bmi);
				var age = parseInt("<?php echo $this->session->userdata('agealldays');?>");
				if (age <= 6574.5) {
					var text = bmi;
				} else {
					if (bmi < 19) {
						var a = " - Underweight";
					}
					if (bmi >=19 && bmi <=25) {
						var a = " - Desirable";
					}
					if (bmi >=26 && bmi <=29) {
						var a = " - Prone to health risks";
					}
					if (bmi >=30 && bmi <=40) {
						var a = " - Obese";
					}
					if (bmi >40){
						var a = " - Morbidly obese";
					}
					var text = bmi + a;
				}
				$("#vitals_bmi_display").html(text);
			}
		}
	});
	$("#vitals_temp").blur(function(){
		var a = $("#vitals_temp").val();
		if (a != '') {
			if (a > 100.4) {
				$("#vitals_temp").css("color","red");
			} else {
				$("#vitals_temp").css("color","black");
			}
			if (a > 106 || a < 93) {
				$.jGrowl('Invalid temperature value!');
				$("#vitals_temp").val('');
				$("#vitals_temp").css("color","black");
			}
		}
	});
	$("#vitals_bp_systolic").blur(function(){
		var a = $("#vitals_bp_systolic").val();
		if (a != '') {
			if (a > 140 || a < 80) {
				$("#vitals_bp_systolic").css("color","red");
			} else {
				$("#vitals_bp_systolic").css("color","black");
			}
			if (a > 250 || a < 50) {
				$.jGrowl('Invalid value!');
				$("#vitals_bp_systolic").val('');
				$("#vitals_bp_systolic").css("color","black");
			}
		}
	});
	$("#vitals_bp_diastolic").blur(function(){
		var a = $("#vitals_bp_diastolic").val();
		if (a != '') {
			if (a > 90 || a < 50) {
				$("#vitals_bp_diastolic").css("color","red");
			} else {
				$("#vitals_bp_diastolic").css("color","black");
			}
			if (a > 200 || a < 30) {
				$.jGrowl('Invalid value!');
				$("#vitals_bp_diastolic").val('');
				$("#vitals_bp_diastolic").css("color","black");
			}
		}
	});
	$("#vitals_pulse").blur(function(){
		var a = $("#vitals_pulse").val();
		if (a != '') {
			if (a > 140 || a < 50) {
				$("#vitals_pulse").css("color","red");
			} else {
				$("#vitals_pulse").css("color","black");
			}
			if (a > 250 || a < 30) {
				$.jGrowl('Invalid value!');
				$("#vitals_pulse").val('');
				$("#vitals_pulse").css("color","black");
			}
		}
	});
	$("#vitals_respirations").blur(function(){
		var a = $("#vitals_respirations").val();
		if (a != '') {
			if (a > 35 || a < 10) {
				$("#vitals_respirations").css("color","red");
			} else {
				$("#vitals_respirations").css("color","black");
			}
			if (a > 50 || a < 5) {
				$.jGrowl('Invalid value!');
				$("#vitals_respirations").val('');
				$("#vitals_respirations").css("color","black");
			}
		}
	});
	$("#vitals_o2_sat").blur(function(){
		var a = $("#vitals_o2_sat").val();
		if (a != '') {
			if (a < 90) {
				$("#vitals_o2_sat").css("color","red");
			} else {
				$("#vitals_o2_sat").css("color","black");
			}
			if (a > 100 || a < 50) {
				$.jGrowl('Invalid value!');
				$("#vitals_o2_sat").val('');
				$("#vitals_o2_sat").css("color","black");
			}
		}
	});
	$("#graph_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true
	});
	$("#graph_load").dialog({
		height: 100,
		autoOpen: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		modal: true
	});
	$("#weight_chart").click(function(){
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/growth_chart/index/weight-age');?>/",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				var note = $("#vitals_vitals_other").val();
				if (note == '') {
					var newnote = 'Weight-to-age percentile: ' + data.percentile + '.';
				} else {
					var newnote = note + '  Weight-to-age percentile: ' + data.percentile + '.';
				}
				$("#vitals_vitals_other").val(newnote);
				var str = $("#vitals_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/vitals_save');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var a = $("#vitals_weight").val();
							var b = $("#vitals_height").val();
							var c = $("#vitals_BMI").val();
							var d = $("#vitals_headcircumference").val();
							var e = $("#vitals_temp").val();
							var f = $("#vitals_temp_method").val();
							var g = $("#vitals_bp_systolic").val();
							var h = $("#vitals_bp_diastolic").val();
							var i = $("#vitals_bp_position").val();
							var j = $("#vitals_pulse").val();
							var k = $("#vitals_respirations").val();
							var l = $("#vitals_o2_sat").val();
							var m = $("#vitals_vitals_other").val();
							$("#vitals_weight_old").val(a);
							$("#vitals_height_old").val(b);
							$("#vitals_BMI_old").val(c);
							$("#vitals_headcircumference_old").val(d);
							$("#vitals_temp_old").val(e);
							$("#vitals_temp_method_old").val(f);
							$("#vitals_bp_systolic_old").val(g);
							$("#vitals_bp_diastolic_old").val(h);
							$("#vitals_bp_position_old").val(i);
							$("#vitals_pulse_old").val(j);
							$("#vitals_respirations_old").val(k);
							$("#vitals_o2_sat_old").val(l);
							$("#vitals_vitals_other_old").val(m);
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		});
	});
	$("#height_chart").click(function(){
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/growth_chart/index/height-age');?>/",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				var note = $("#vitals_vitals_other").val();
				if (note == '') {
					var newnote = 'Height-to-age percentile: ' + data.percentile + '.';
				} else {
					var newnote = note + '  Height-to-age percentile: ' + data.percentile + '.';
				}
				$("#vitals_vitals_other").val(newnote);
				var str = $("#vitals_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/vitals_save');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var a = $("#vitals_weight").val();
							var b = $("#vitals_height").val();
							var c = $("#vitals_BMI").val();
							var d = $("#vitals_headcircumference").val();
							var e = $("#vitals_temp").val();
							var f = $("#vitals_temp_method").val();
							var g = $("#vitals_bp_systolic").val();
							var h = $("#vitals_bp_diastolic").val();
							var i = $("#vitals_bp_position").val();
							var j = $("#vitals_pulse").val();
							var k = $("#vitals_respirations").val();
							var l = $("#vitals_o2_sat").val();
							var m = $("#vitals_vitals_other").val();
							$("#vitals_weight_old").val(a);
							$("#vitals_height_old").val(b);
							$("#vitals_BMI_old").val(c);
							$("#vitals_headcircumference_old").val(d);
							$("#vitals_temp_old").val(e);
							$("#vitals_temp_method_old").val(f);
							$("#vitals_bp_systolic_old").val(g);
							$("#vitals_bp_diastolic_old").val(h);
							$("#vitals_bp_position_old").val(i);
							$("#vitals_pulse_old").val(j);
							$("#vitals_respirations_old").val(k);
							$("#vitals_o2_sat_old").val(l);
							$("#vitals_vitals_other_old").val(m);
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		});
	});
	$("#hc_chart").click(function(){
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/growth_chart/index/head-age');?>/",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				var note = $("#vitals_vitals_other").val();
				if (note == '') {
					var newnote = 'Head circumference-to-age percentile: ' + data.percentile + '.';
				} else {
					var newnote = note + '  Head circumference-to-age percentile: ' + data.percentile + '.';
				}
				$("#vitals_vitals_other").val(newnote);
				var str = $("#vitals_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/vitals_save');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var a = $("#vitals_weight").val();
							var b = $("#vitals_height").val();
							var c = $("#vitals_BMI").val();
							var d = $("#vitals_headcircumference").val();
							var e = $("#vitals_temp").val();
							var f = $("#vitals_temp_method").val();
							var g = $("#vitals_bp_systolic").val();
							var h = $("#vitals_bp_diastolic").val();
							var i = $("#vitals_bp_position").val();
							var j = $("#vitals_pulse").val();
							var k = $("#vitals_respirations").val();
							var l = $("#vitals_o2_sat").val();
							var m = $("#vitals_vitals_other").val();
							$("#vitals_weight_old").val(a);
							$("#vitals_height_old").val(b);
							$("#vitals_BMI_old").val(c);
							$("#vitals_headcircumference_old").val(d);
							$("#vitals_temp_old").val(e);
							$("#vitals_temp_method_old").val(f);
							$("#vitals_bp_systolic_old").val(g);
							$("#vitals_bp_diastolic_old").val(h);
							$("#vitals_bp_position_old").val(i);
							$("#vitals_pulse_old").val(j);
							$("#vitals_respirations_old").val(k);
							$("#vitals_o2_sat_old").val(l);
							$("#vitals_vitals_other_old").val(m);
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		});
	});
	$("#bmi_chart").click(function(){
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				},
				labels: {
					step: 180
				},
				categories: []
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'spline', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/growth_chart/index/bmi-age');?>/",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.xAxis.categories = data.categories;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				var note = $("#vitals_vitals_other").val();
				if (note == '') {
					var newnote = 'BMI-to-age percentile: ' + data.percentile + '.';
				} else {
					var newnote = note + '  BMI-to-age percentile: ' + data.percentile + '.';
				}
				$("#vitals_vitals_other").val(newnote);
				var str = $("#vitals_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/vitals_save');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var a = $("#vitals_weight").val();
							var b = $("#vitals_height").val();
							var c = $("#vitals_BMI").val();
							var d = $("#vitals_headcircumference").val();
							var e = $("#vitals_temp").val();
							var f = $("#vitals_temp_method").val();
							var g = $("#vitals_bp_systolic").val();
							var h = $("#vitals_bp_diastolic").val();
							var i = $("#vitals_bp_position").val();
							var j = $("#vitals_pulse").val();
							var k = $("#vitals_respirations").val();
							var l = $("#vitals_o2_sat").val();
							var m = $("#vitals_vitals_other").val();
							$("#vitals_weight_old").val(a);
							$("#vitals_height_old").val(b);
							$("#vitals_BMI_old").val(c);
							$("#vitals_headcircumference_old").val(d);
							$("#vitals_temp_old").val(e);
							$("#vitals_temp_method_old").val(f);
							$("#vitals_bp_systolic_old").val(g);
							$("#vitals_bp_diastolic_old").val(h);
							$("#vitals_bp_position_old").val(i);
							$("#vitals_pulse_old").val(j);
							$("#vitals_respirations_old").val(k);
							$("#vitals_o2_sat_old").val(l);
							$("#vitals_vitals_other_old").val(m);
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		});
	});
	$("#weight_height_chart").click(function(){
		$("#graph_load").dialog('open');
		var options = {
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 50,
				width: 750
			},
			title: {
				text: '',
				x: -20
			},
			xAxis: {
				title: {
					text: ''
				}
			},
			yAxis: {
				title: {
					text: ''
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			tooltip: {
				enabled: false
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			series: [
				{name: '95%', type: 'spline', data: []},
				{name: '90%', type: 'spline', data: []},
				{name: '75%', type: 'spline', data: []},
				{name: '50%', type: 'spline', data: []},
				{name: '25%', type: 'spline', data: []},
				{name: '10%', type: 'spline', data: []},
				{name: '5%', type: 'spline', data: []},
				{type: 'line', data: []}
			],
			credits: {
				href: 'http://noshemr.wordpress.com',
				text: 'NOSH ChartingSystem'
			},
			plotOptions: {
				spline: {
					marker: {
						enabled: false
					}
				}
			}
		};
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/growth_chart/index/weight-height');?>/",
			dataType: "json",
			success: function(data){
				options.title.text = data.title;
				options.xAxis.title.text = data.xaxis;
				options.yAxis.title.text = data.yaxis;
				options.series[0].data = data.P95;
				options.series[1].data = data.P90;
				options.series[2].data = data.P75;
				options.series[3].data = data.P50;
				options.series[4].data = data.P25;
				options.series[5].data = data.P10;
				options.series[6].data = data.P5;
				options.series[7].data = data.patient;
				options.series[7].name = data.patientname;
				var chart = new Highcharts.Chart(options);
				$("#graph_load").dialog('close');
				$("#graph_dialog").dialog('open');
				var note = $("#vitals_vitals_other").val();
				if (note == '') {
					var newnote = 'Weight-to-height percentile: ' + data.percentile + '.';
				} else {
					var newnote = note + '  Weight-to-height percentile: ' + data.percentile + '.';
				}
				$("#vitals_vitals_other").val(newnote);
				var str = $("#vitals_form").serialize();
				if(str){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/encounters/vitals_save');?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var a = $("#vitals_weight").val();
							var b = $("#vitals_height").val();
							var c = $("#vitals_BMI").val();
							var d = $("#vitals_headcircumference").val();
							var e = $("#vitals_temp").val();
							var f = $("#vitals_temp_method").val();
							var g = $("#vitals_bp_systolic").val();
							var h = $("#vitals_bp_diastolic").val();
							var i = $("#vitals_bp_position").val();
							var j = $("#vitals_pulse").val();
							var k = $("#vitals_respirations").val();
							var l = $("#vitals_o2_sat").val();
							var m = $("#vitals_vitals_other").val();
							$("#vitals_weight_old").val(a);
							$("#vitals_height_old").val(b);
							$("#vitals_BMI_old").val(c);
							$("#vitals_headcircumference_old").val(d);
							$("#vitals_temp_old").val(e);
							$("#vitals_temp_method_old").val(f);
							$("#vitals_bp_systolic_old").val(g);
							$("#vitals_bp_diastolic_old").val(h);
							$("#vitals_bp_position_old").val(i);
							$("#vitals_pulse_old").val(j);
							$("#vitals_respirations_old").val(k);
							$("#vitals_o2_sat_old").val(l);
							$("#vitals_vitals_other_old").val(m);
						}
					});
				} else {
					$.jGrowl("Please complete the form");
				}
			}
		});
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_vitals');?>",
		dataType: "json",
		success: function(data){
			if (data == '') {
				$("#vitals_temp_method_old").val('Oral');
				$("#vitals_bp_position_old").val('Sitting');
			} else {
				$("#vitals_weight").val(data.weight);
				$("#vitals_height").val(data.height);
				$("#vitals_BMI").val(data.BMI);
				$("#vitals_headcircumference").val(data.headcircumference);
				$("#vitals_temp").val(data.temp);
				$("#vitals_temp_method").val(data.temp_method);
				$("#vitals_bp_systolic").val(data.bp_systolic);
				$("#vitals_bp_diastolic").val(data.bp_diastolic);
				$("#vitals_bp_position").val(data.bp_position);
				$("#vitals_pulse").val(data.pulse);
				$("#vitals_respirations").val(data.respirations);
				$("#vitals_o2_sat").val(data.o2_sat);
				$("#vitals_vitals_other").val(data.vitals_other);
				$("#vitals_weight_old").val(data.weight);
				$("#vitals_height_old").val(data.height);
				$("#vitals_BMI_old").val(data.BMI);
				$("#vitals_headcircumference_old").val(data.headcircumference);
				$("#vitals_temp_old").val(data.temp);
				$("#vitals_temp_method_old").val(data.temp_method);
				$("#vitals_bp_systolic_old").val(data.bp_systolic);
				$("#vitals_bp_diastolic_old").val(data.bp_diastolic);
				$("#vitals_bp_position_old").val(data.bp_position);
				$("#vitals_pulse_old").val(data.pulse);
				$("#vitals_respirations_old").val(data.respirations);
				$("#vitals_o2_sat_old").val(data.o2_sat);
				$("#vitals_vitals_other_old").val(data.vitals_other);
			}
		}
	});
	function vitals_autosave() {
		var old3a = $("#vitals_weight_old").val();
		var new3a = $("#vitals_weight").val();
		var old3b = $("#vitals_height_old").val();
		var new3b = $("#vitals_height").val();
		var old3c = $("#vitals_BMI_old").val();
		var new3c = $("#vitals_BMI").val();
		var old3d = $("#vitals_headcircumference_old").val();
		var new3d = $("#vitals_headcircumference").val();
		var old3e = $("#vitals_temp_old").val();
		var new3e = $("#vitals_temp").val();
		var old3f = $("#vitals_temp_method_old").val();
		var new3f = $("#vitals_temp_method").val();
		var old3g = $("#vitals_bp_systolic_old").val();
		var new3g = $("#vitals_bp_systolic").val();
		var old3h = $("#vitals_bp_diastolic_old").val();
		var new3h = $("#vitals_bp_diastolic").val();
		var old3i = $("#vitals_bp_position_old").val();
		var new3i = $("#vitals_bp_position").val();
		var old3j = $("#vitals_pulse_old").val();
		var new3j = $("#vitals_pulse").val();
		var new3k = $("#vitals_respirations").val();
		var old3k = $("#vitals_respirations_old").val();
		var old3l = $("#vitals_o2_sat_old").val();
		var new3l = $("#vitals_o2_sat").val();
		var old3m = $("#vitals_vitals_other_old").val();
		var new3m = $("#vitals_vitals_other").val();
		if (old3a != new3a || old3b != new3b || old3c != new3c || old3d != new3d || old3e != new3e || old3f != new3f || old3g != new3g || old3h != new3h || old3i != new3i || old3j != new3j || old3k != new3k || old3l != new3l || old3m != new3m) {
			var vitals_str = $("#vitals_form").serialize();
			if(vitals_str){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('provider/encounters/vitals_save');?>",
					data: vitals_str,
					success: function(data){
						$.jGrowl(data);
						var a = $("#vitals_weight").val();
						var b = $("#vitals_height").val();
						var c = $("#vitals_BMI").val();
						var d = $("#vitals_headcircumference").val();
						var e = $("#vitals_temp").val();
						var f = $("#vitals_temp_method").val();
						var g = $("#vitals_bp_systolic").val();
						var h = $("#vitals_bp_diastolic").val();
						var i = $("#vitals_bp_position").val();
						var j = $("#vitals_pulse").val();
						var k = $("#vitals_respirations").val();
						var l = $("#vitals_o2_sat").val();
						var m = $("#vitals_vitals_other").val();
						$("#vitals_weight_old").val(a);
						$("#vitals_height_old").val(b);
						$("#vitals_BMI_old").val(c);
						$("#vitals_headcircumference_old").val(d);
						$("#vitals_temp_old").val(e);
						$("#vitals_temp_method_old").val(f);
						$("#vitals_bp_systolic_old").val(g);
						$("#vitals_bp_diastolic_old").val(h);
						$("#vitals_bp_position_old").val(i);
						$("#vitals_pulse_old").val(j);
						$("#vitals_respirations_old").val(k);
						$("#vitals_o2_sat_old").val(l);
						$("#vitals_vitals_other_old").val(m);
					}
				});
			} else {
				$.jGrowl("Please complete the form");
			}
		}
	}
	setInterval(vitals_autosave, 10000);
</script>
