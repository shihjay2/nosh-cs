<script type="text/javascript">
	$('#providers_datepicker').datepicker({
		inline: true,
		onSelect: function(dateText, inst) {
			var d = new Date(dateText);
			$('#providers_calendar').fullCalendar('gotoDate', d);
		}
	});
	$("#event_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 450, 
		width: 600, 
		modal: true, 
		buttons: { 
			'Save': function() { 
				var end= $("#end").val();
				var visit_type = $("#visit_type").val();
				var pid = $("#pid").val();
				if (pid == '') {
					var reason = $("#reason").val();
					$("#title").val(reason);
				}
				if ($("#repeat").val() != '' && $("#event_id").val() != '' && $("#event_id").val().indexOf("R") === -1) {
					var event_id = $("#event_id").val();
					$("#event_id").val("N" + event_id);
				}
				if ($("#repeat").val() == '' && $("#event_id").val() != '' && $("#event_id").val().indexOf("R") !== -1) {
					var event_id1 = $("#event_id").val();
					$("#event_id").val("N" + event_id1);
				}
				var str = $("#event_form").serialize();
				if(str){
					if (visit_type == '' && end == '') {
						$.jGrowl("No visit type or end time selected!");
					} else {
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('admin/schedule/edit_event/');?>",
							data: str,
							success: function(data){
								$("#providers_calendar").fullCalendar('removeEvents');
								$("#event_dialog").dialog('close');
								$("#event_form").clearForm();
								$("#providers_calendar").fullCalendar('refetchEvents');
							}
						});
					}
				} else {
					$.jGrowl("Error saving appointment!");
				}
			}, 
			Cancel: function() { 
				$("#event_dialog").dialog('close');
				$("#event_form").clearForm();
			}
		},
		close: function(event, ui) {
			$("#event_form").clearForm();
		}
	});
	$("#patient_appt_button").button();
	$('#patient_appt_button').click(function() {
		$("#patient_appt").show("fast");
		$("#start_form").show("fast");
		$("#reason_form").show("fast");
		$("#other_event").hide("fast");
		$("#event_choose").hide("fast");
		$("#patient_search").focus();
	});
	$("#event_appt_button").button();
	$('#event_appt_button').click(function() {
		$("#patient_appt").hide("fast");
		$("#other_event").show("fast");
		$("#start_form").show("fast");
		$("#reason_form").show("fast");
		$("#event_choose").hide("fast");
		$("#reason").focus();
	});
	$("#patient_search").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search');?>",
				dataType: "json",
				type: "POST",
				data: req,
				success: function(data){
					if(data.response =='true'){
						add(data.message);
					}				
				}
			});
		},
		minLength: 3,
		select: function(event, ui){
			$("#pid").val(ui.item.id);
			$("#title").val(ui.item.value);
		}
	});
	$("#start_date").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#start_time').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 20
	});
	$('#end').timepicker({
		'scrollDefaultNow': true,
		'timeFormat': 'h:i A',
		'step': 20
	});
	$('#visit_type').change(function() {
		var visit_type_select = $("#visit_type").val();
		if (visit_type_select != ''){
			$("#end_row").hide("fast");
			$("#end").val('');
		} else {
			$("#end_row").show("fast");
		}
	});
	$('#repeat').change(function() {
		var repeat_select = $("#repeat").val();
		if (repeat_select != ''){
			$("#until_row").show("fast");
		} else {
			$("#until_row").hide("fast");
			$("#until").val('');
		}
	});
	$("#until").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
	$('#delete_event').click(function() {
		if(confirm('Are you sure you want to delete this appointment?')){ 
			var appt_id = $("#event_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/schedule/delete_event/');?>",
				data: "appt_id=" + appt_id,
				success: function(data){
					$("#providers_calendar").fullCalendar('removeEvents');
					$("#event_dialog").dialog('close');
					$("#providers_calendar").fullCalendar('refetchEvents');
				}
			});
		} 
	});
	$('#openChart1').click(function() {
		var pid = $("#pid").val();
		if(pid){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('search/openchart/');?>",
				data: "pid=" + pid,
				success: function(data){
					window.location = "<?php echo site_url('search/openchart1/');?>";
				}
			});
		} else {
			$.jGrowl("Please enter patient to open chart!");
		}
	});
	$('#providers_calendar').fullCalendar({
		weekends: <?php echo $weekends;?>,
		minTime: '<?php echo $minTime;?>',
		maxTime: '<?php echo $maxTime;?>',
		theme: true,
		allDayDefault: false,
		slotMinutes: 15,
		defaultView: 'agendaWeek',
		aspectRatio: 0.8,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'agendaWeek,agendaDay'
		},
		editable: true,
		events: function(start, end, callback) {
			var starttime = Math.round(start.getTime() / 1000);
			var endtime = Math.round(end.getTime() / 1000);
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/schedule/provider_schedule/');?>",
				dataType: 'json',
				data: "start=" + starttime + "&end=" + endtime,
				success: function(events) {
					callback(events);
				}
			});
		},
		dayClick: function(date, allDay, jsEvent, view) {
			if (allDay) {
				alert('Clicked on the entire day: ' + date);
			} else {
				$("#event_dialog").dialog('open');
				$("#title").focus();
				$("#start_date").val($.fullCalendar.formatDate(date, 'MM/dd/yyyy'));
				$("#start_time").val($.fullCalendar.formatDate(date, 'hh:mmTT'));
				$("#end").val('');
				$("#visit_type").val('');
				$("#end_row").show("fast");
				$("#title").val('');
				$("#reason").val('');
				$("#until").val('');
				$("#until_row").hide("fast");
				$('#repeat').val('');
				$("#delete_form").hide("fast");
				$("#patient_appt").hide("fast");
				$("#other_event").hide("fast");
				$("#until_row").hide("fast");
				$("#start_form").hide("fast");
				$("#reason_form").hide("fast");
				$("#event_choose").show("fast");
			}
		},
		eventClick: function(calEvent, jsEvent, view) {
			if (calEvent.editable != false) {
				$("#event_dialog").dialog('open');
				$("#title").focus();
			}
			$("#event_id").val(calEvent.id);
			$("#event_id_span").text(calEvent.id);
			$("#pid").val(calEvent.pid);
			$("#pid_span").text(calEvent.pid);
			$("#timestamp_span").text(calEvent.timestamp);
			$("#start_date").val($.fullCalendar.formatDate(calEvent.start, 'MM/dd/yyyy'));
			$("#start_time").val($.fullCalendar.formatDate(calEvent.start, 'hh:mmTT'));
			$("#end").val($.fullCalendar.formatDate(calEvent.end, 'hh:mmTT'));
			$("#title").val(calEvent.title);
			$("#visit_type").val(calEvent.visit_type);
			if (calEvent.visit_type){
				$("#other_event").hide("fast");
				$("#patient_appt").show("fast");
				$("#patient_search").val(calEvent.title);
				$("#end").val('');
				$("#patient_search").focus();
			} else {
				$("#other_event").show("fast");
				$("#patient_appt").hide("fast");
				$("#reason").focus();
			}
			$("#reason").val(calEvent.reason);
			$("#repeat").val(calEvent.repeat);
			$("#until").val(calEvent.until);
			var repeat_select = $("#repeat").val();
			if (repeat_select != ''){
				$("#until_row").show("fast");
			} else {
				$("#until_row").hide("fast");
				$("#until").val('');
			}
			$("#status").val(calEvent.status);
			$("#delete_form").show("fast");
			$("#event_choose").hide("fast");
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			var start = Math.round(event.start.getTime() / 1000);
			var end = Math.round(event.end.getTime() / 1000);
			if(start){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('admin/schedule/drag_event/');?>",
					data: "start=" + start + "&end=" + end + "&id=" + event.id,
					success: function(data){
						$.jGrowl("Event updated!");
					}
				});
			} else {
				revertFunc();
			}
			$('.fc-event').each(function(){
				$(this).tooltip('disable');
			});
		},
		eventResize: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			var start = Math.round(event.start.getTime() / 1000);
			var end = Math.round(event.end.getTime() / 1000);
			if(start){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('admin/schedule/drag_event/');?>",
					data: "start=" + start + "&end=" + end + "&id=" + event.id,
					success: function(data){
						$.jGrowl("Event updated!");
					}
				});
			} else {
				revertFunc();
			}
			$('.fc-event').each(function(){
				$(this).tooltip('disable');
			});
		},
		eventRender: function(event, element) {
			var display = 'Reason: ' + event.reason + '<br>Status: ' + event.status;
			element.tooltip({
				items: element,
				hide: false,
				show: false,
				content: display
			});
			element.tooltip('enable');
		}
	});
</script>
<div style="width:250px;float:left;"><div id="providers_datepicker"></div></div>
<div style="width:650px;float:left;"><div id="providers_calendar" ></div></div>
<div id="event_dialog" title="Schedule an Appointment" style="font-size: 0.9em">
	<form id="event_form">
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="event_id" id="event_id"/>
		<input type="hidden" name="title" id="title"/>
		<div id="event_choose">
			Choose a type of event: 
			<button type="button" id="patient_appt_button">Patient Appointment</button> 
			<button type="button" id="event_appt_button">Other Event</button>
		</div>
		<div id="delete_form">
			<a href="#" id="delete_event" style="float:right;text-align:right">[Delete]</a>
			Event ID: <span id="event_id_span"></span> | Patient ID: <span id="pid_span"></span> <input type="button" value="Open Chart" id="openChart1" class="ui-button ui-state-default ui-corner-all"/> | Status: 
			<select name="status" id="status" class="text ui-widget-content ui-corner-all">
				<option value="">None</option>
				<option value="Pending">Pending</option>
				<option value="Reminder Sent">Reminder Sent</option>
				<option value="Attended">Attended</option>
				<option value="LMC">Last Minute Cancellation</option>
				<option value="DNKA">Did Not Keep Appointment</option>
			</select>
			<br>
			<span id="timestamp_span"></span><br>
			<hr/>
		</div>
		<div id="start_form">
			<table>
				<tr>
					<td><label for="start_date">Start Date:</label></td>
					<td><input type="text" name="start_date" id="start_date" class="text ui-widget-content ui-corner-all"></td>
				</tr>
				<tr>
					<td><label for="start_time">Start Time:</label></td>
					<td><input type="text" name="start_time" id="start_time" class="text ui-widget-content ui-corner-all"></td>
				</tr>
			</table>
			<br>
		</div>
		<div id="patient_appt">
			<label for="patient_search">Patient:</label><br>
			<input type="text" name="patient_search" id="patient_search" style="width:400px" class="text ui-widget-content ui-corner-all" /><br>
			<label for="visit_type">Visit Type:</label><br>
			<select name="visit_type" id="visit_type" class="text ui-widget-content ui-corner-all"><?php if ($visit_type_select != '') {echo $visit_type_select;}?></select><br>
		</div>
		<div id="reason_form">
			<label for="reason">Reason:</label><br>
			<textarea name="reason" id="reason" style="width:400px" rows="3" class="text ui-widget-content ui-corner-all"></textarea><br>
		</div>
		<div id="other_event">
			<label for="end">End Time:</label><br>
			<input type="text" name="end" id="end" class="text ui-widget-content ui-corner-all"><br>
			<label for="repeat">Repeat:</label><br>
			<select name="repeat" id="repeat" class="text ui-widget-content ui-corner-all">
				<option value="">None</option>
				<option value="86400">Every Day</option>
				<option value="604800">Every Week</option>
				<option value="1209600">Every Other Week</option>
			</select><br>
			<div id="until_row">
				<label for="until">Until (Leave this field blank if repeat goes on forever):</label><br>
				<input type="text" name="until" id="until" class="text ui-widget-content ui-corner-all" /><br>
			</div>
		</div>
	</form>
</div>
