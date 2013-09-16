<script type="text/javascript">
$(document).ready(function() {
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
		height: 400, 
		width: 600, 
		modal: true, 
		buttons: { 
			'Save': function() { 
				var start = $("#start").text();
				if(start){
					var a = $("#visit_type");
					var b = $("#reason");
					var bValid = true;
					bValid = bValid && checkEmpty(a,"Visit Type");
					bValid = bValid && checkEmpty(b,"Reason");
					if (bValid) {
						var visit_type = $("#visit_type").val();
						var reason = $("#reason").val();
						var event_id = $("#event_id").val();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('patient/schedule/edit_event/');?>",
							data: "start=" + start + "&visit_type=" + visit_type + "&reason=" + reason + "&id=" + event_id,
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
	$('#delete_event').click(function() {
		if(confirm('Are you sure you want to delete this appointment?')){ 
			var appt_id = $("#event_id").val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('patient/schedule/delete_event/');?>",
				data: "appt_id=" + appt_id,
				success: function(data){
					$("#event_dialog").dialog('close');
					$("#providers_calendar").fullCalendar('removeEvents');
					$("#providers_calendar").fullCalendar('refetchEvents');
				}
			});
		} 
	});
	$('#providers_calendar').fullCalendar({
		weekends: <?php echo $weekends;?>,
		minTime: '<?php echo $minTime;?>',
		maxTime: '<?php echo $maxTime;?>',
		theme: true,
		allDayDefault: false,
		slotMinutes: 15,
		aspectRatio: 0.8,
		defaultView: 'agendaWeek',
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
				url: "<?php echo site_url('patient/schedule/provider_schedule/');?>",
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
				var start1 = new Date(date.toString());
				var start = Math.round(start1.getTime() / 1000);
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('patient/schedule/check_overlap/');?>",
					data: "start=" + start,
					dataType: "json",
					success: function(data){
						if (data.response == 'No') {
							$.jGrowl(data.message);
						} else {
							$("#event_dialog").dialog('open');
							$("#visit_type").focus();
							$("#start").text($.fullCalendar.formatDate(date, 'dddd, MM/dd/yyyy, hh:mmTT'));
							$("#visit_type").val('');
							$("#reason").val('');
							$("#delete_form").hide("fast");
						}
					}
				});
			}
		},
		eventClick: function(calEvent, jsEvent, view) {
			if (calEvent.editable != false) {
				$("#event_dialog").dialog('open');
				$("#visit_type").focus();
			}
			$("#event_id_span").text(calEvent.id);
			$("#event_id").val(calEvent.id);
			$("#start").text($.fullCalendar.formatDate(calEvent.start, 'dddd, MM/dd/yyyy, hh:mmTT'));
			$("#pid_span").text(calEvent.pid);
			$("#pid").val(calEvent.pid);
			$("#visit_type").val(calEvent.visit_type);
			$("#reason").val(calEvent.reason);
			$("#delete_form").show("fast");
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			var start = Math.round(event.start.getTime() / 1000);
			var end = Math.round(event.end.getTime() / 1000);
			if(start){
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('patient/schedule/drag_event/');?>",
					data: "start=" + start + "&end=" + end + "&id=" + event.id,
					dataType: "json",
					success: function(data){
						if (data.response == 'No') {
							$.jGrowl(data.message);
							revertFunc();
						} else {
							$.jGrowl('Event updated!');
						}
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
					url: "<?php echo site_url('patient/schedule/drag_event/');?>",
					data: "start=" + start + "&end=" + end + "&id=" + event.id,
					dataType: "json",
					success: function(data){
						if (data.response == 'No') {
							$.jGrowl(data.message);
							revertFunc();
						} else {
							$.jGrowl('Event updated!');
						}
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
});
</script>
Step 2: Click on an open time slot on the schedule.  Appointments can only be set at each hour.<br>
There can be no overlapping appointments with an existing appointment.<br>
You will be notified if there is a problem with your appointment request.<br>
To delete an existing appointment, double click on the appointment and on the dialog box on the upper right hand corner, click Delete.<br><br>
<div style="width:250px;float:left;"><div id="providers_datepicker"></div></div>
<div style="width:650px;float:left;"><div id="providers_calendar" ></div></div>
<div id="event_dialog" title="Schedule an Appointment">
	<form name="event_form" id="event_form">
		<input type="hidden" name="pid" id="pid"/>
		<input type="hidden" name="event_id" id="event_id"/>
		<div id="delete_form">
			<a href="javascript:{}" id="delete_event" style="float:right;text-align:right">[Delete]</a>
			Event ID: <span id="event_id_span"></span> | Patient ID: <span id="pid_span"></span><br>
			<hr class="ui-state-default"/>
		</div>
		Start Time: <span id="start"></span><br>
		<label for="visit_type">Type of Visit:</label><br>
		<select name="visit_type" id="visit_type" class="text ui-widget-content ui-corner-all"><?php if ($visit_type_select != '') {echo $visit_type_select;}?></select><br><br>
		<label for="reason">Reason for Visit</label><br>
		<textarea name="reason" id="reason" style="width:300px;height:100px" class="text ui-widget-content ui-corner-all"></textarea><br><br>
	</form>
</div>
