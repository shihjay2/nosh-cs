<div id="letter_dialog" title="Letter Writer">
	<form id="letter_form">
		<input type="hidden" name="address_id" id="letter_to_id"/>
		<input type="hidden" id="letter_eid"/>
		<input type="hidden" id="letter_type"/>
		<button type="button" id="save_letter">Save</button> 
		<button type="button" id="cancel_letter">Cancel</button>
		<hr class="ui-state-default"/>
		<span style="float:left;width:310px">
			To:<br><input type="text" name="letter_to" id="letter_to" style="width:290px" class="text ui-widget-content ui-corner-all" /><br>
			Preview:<br><textarea style="width:290px" rows="16" name="letter_body" id="letter_body" class="text ui-widget-content ui-corner-all"></textarea>
		</span>
	</form>
	<span>
		<br><button type="button" id="letter_to_whom">To Whom It May Concern</button><br>
		Choose Template: <select id="letter_template_choose_id" class="letter_template_choose text ui-widget-content ui-corner-all"></select><br>
		<br>
		<button type="button" id="letter_template_save">Copy</button>
		<button type="button" id="letter_reset">Clear</button>
		<div class="letter_template_div">
			<br><form id="letter_template_form" class="letter_template_form ui-widget"></form>
		</div>
	</span>
</div>
<script type="text/javascript">
	$("#letter_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		beforeclose: function(event, ui) {
			var a = $("#letter_to").val();
			var b = $("#letter_body").val();
			if(a != '' || b != ''){
				if(confirm('You have not completed editing the letter.  Are you sure you want to close this window?')){ 
					$('#letter_form').clearForm();
					return true;
				} else {
					return false;
				}
			} else {
				$('#letter_form').clearForm();
				return true;
			}
		}
	});
	$("#letter_to").autocomplete({
		source: function (req, add){
			$.ajax({
				url: "<?php echo site_url('search/all_contacts1');?>",
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
		minLength: 2,
		select: function(event, ui){
			$('#letter_to_id').val(ui.item.id);
		}
	});
	$("#letter_to_whom").button();
	$("#letter_to_whom").click(function() {
		$('#letter_to').val('To whom it may concern');
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/chartmenu/letter_template_select_list');?>",
		dataType: "json",
		success: function(data){
			$('#letter_template_choose_id').addOption({"":"*Select a template"});
			$('#letter_template_choose_id').addOption(data.options);
			$('#letter_template_choose_id').sortOptions();
			$('#letter_template_choose_id').val("");
		}
	});
	$('#letter_template_choose_id').change(function(){
		var a = $(this).val();
		$('#letter_template_form').html('');
		if (a != '') {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/chartmenu/get_letter_template');?>" + "/" + a,
				dataType: "json",
				success: function(data){
					$('#letter_template_form').dform(data);
					$('#letter_template_form').find('input').first().focus();
					$('#letter_template_form').find('.letter_buttonset').buttonset();
					$(".letter_select").chosen();
					$(".letter_date").mask("99/99/9999").datepicker({showOn: 'button', buttonImage: '<?php echo base_url()."images/calendar.gif";?>', buttonImageOnly: true});
				}
			});
		}
	});
	$("#letter_template_save").button({icons: {primary: "ui-icon-arrowthick-1-w"}}).click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/letter_template_construct');?>",
			dataType: "json",
			success: function(data){
				var b = $(".letter_hidden").val();
				var start_date = $.datepicker.formatDate('MM d, yy', parse_date1($('.letter_start_date').val()));
				var return_date = $.datepicker.formatDate('MM d, yy', parse_date1($('.letter_return_date').val()));
				var end_date = $.datepicker.formatDate('MM d, yy', parse_date1($('.letter_end_date').val()));
				b = b.replace('_firstname', data.firstname);
				b = b.replace('  _firstname', '  ' + data.firstname);
				b = b.replace('_start_date', start_date);
				b = b.replace('_return_date', return_date);
				b = b.replace('_end_date', end_date);
				var c_array = $('.letter_select').val();
				if (c_array) {
					var c = c_array.join("");
					b = b + "\n" + c;
				}
				var a = $("#letter_body").val();
				a = a.replace(data.start, '');
				if (a == '') {
					$('#letter_body').val(data.start + b);
				} else {
					$('#letter_body').val(a + "\n" + data.start + b);
				}
				$('#letter_template_form').clearForm();
			}
		});
		
	});
	$('#letter_reset').button({icons: {primary: "ui-icon-close"}}).click(function(){
		$("#letter_body").val('');
		$('#letter_template_form').clearForm();
	});
	$("#save_letter").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});
	$("#save_letter").click(function(){
		var str = $("#letter_form").serialize();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/chartmenu/print_letter');?>",
			data: str,
			dataType: 'json',
			async: false,
			success: function(data){
				if (data.message == 'OK') {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('provider/chartmenu/view_documents1');?>/" + data.id,
						dataType: "json",
						success: function(data){
							//$('#embedURL').PDFDoc( { source : data.html } );
							$("#embedURL").html(data.html);
							$("#document_filepath").val(data.filepath);
							$("#documents_view_dialog").dialog('open');
						}
					});
				} else {
					$.jGrowl(data.message);
				}
			}
		});
		var eid = $("#letter_eid").val();
		if (eid != '') {
			var to = $("#letter_to").val();
			var body = $("#letter_body").val();
			var send = "Letter Written:\nTo: " + to + "\n" + body;
			var old = $("#orders_plan").val();
				if(old){
					var pos = old.lastIndexOf('\n');
					if (pos == -1) {
						var old1 = old + '\n';
					} else {
						var a = old.slice(pos);
						if (a == '') {
							var old1 = old;
						} else {
							var old1 = old + '\n';
						}
					}
				} else {
					var old1 = '';
				}
				$("#orders_plan").val(old1+send);
		}
		$('#letter_form').clearForm();
		$('#letter_template_form').clearForm();
		$('#letter_template').hide('fast');
		$('#letter_dialog').dialog('close');
	});
	$("#cancel_letter").button({
		icons: {
			primary: "ui-icon-close"
		}
	});
	$("#cancel_letter").click(function(){
		$('#letter_form').clearForm();
		$('#letter_template_form').clearForm();
		$('#letter_template').hide('fast');
		$('#letter_dialog').dialog('close');
	});
</script>
