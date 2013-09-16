<div id="configuration_dialog" title="Configuration">
	<div id="configuration_accordion">
		<h3 class="configuration_hpi"><a href="#">HPI Templates</a></h3>
		<div class="configuration_hpi">Coming soon!
		</div>
		<h3 class="configuration_ros"><a href="#">ROS Templates</a></h3>
		<div class="configuration_ros">Coming soon!
		</div>
		<h3 class="configuration_pe"><a href="#">PE Templates</a></h3>
		<div class="configuration_pe">Coming soon!
		</div>
		<h3 class="configuration_orders"><a href="#">Orders - Labs</a></h3>
		<div class="configuration_orders">
			<table id="configuration_orders_labs" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_labs_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_labs_add" class="configuration_orders_button">Add Global</button>
			<button type="button" id="configuration_orders_labs_edit" class="configuration_orders_button">Edit Global</button>
			<button type="button" id="configuration_orders_labs_delete" class="configuration_orders_button">Delete Global</button><br><br>
			<table id="configuration_orders_labs1" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_labs1_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_labs1_add" class="configuration_orders_button">Add Personal</button>
			<button type="button" id="configuration_orders_labs1_edit" class="configuration_orders_button">Edit Personal</button>
			<button type="button" id="configuration_orders_labs1_delete" class="configuration_orders_button">Delete Personal</button><br><br>
		</div>
		<h3 class="configuration_orders"><a href="#">Orders - Imaging</a></h3>
		<div class="configuration_orders">
			<table id="configuration_orders_rad" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_rad_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_rad_add" class="configuration_orders_button">Add Global</button>
			<button type="button" id="configuration_orders_rad_edit" class="configuration_orders_button">Edit Global</button>
			<button type="button" id="configuration_orders_rad_delete" class="configuration_orders_button">Delete Global</button><br><br>
			<table id="configuration_orders_rad1" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_rad1_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_rad1_add" class="configuration_orders_button">Add Personal</button>
			<button type="button" id="configuration_orders_rad1_edit" class="configuration_orders_button">Edit Personal</button>
			<button type="button" id="configuration_orders_rad1_delete" class="configuration_orders_button">Delete Personal</button><br><br>
		</div>
		<h3 class="configuration_orders"><a href="#">Orders - Cardiopulmonary</a></h3>
		<div class="configuration_orders">
			<table id="configuration_orders_cp" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_cp_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_cp_add" class="configuration_orders_button">Add Global</button>
			<button type="button" id="configuration_orders_cp_edit" class="configuration_orders_button">Edit Global</button>
			<button type="button" id="configuration_orders_cp_delete" class="configuration_orders_button">Delete Global</button><br><br>
			<table id="configuration_orders_cp1" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_cp1_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_cp1_add" class="configuration_orders_button">Add Personal</button>
			<button type="button" id="configuration_orders_cp1_edit" class="configuration_orders_button">Edit Personal</button>
			<button type="button" id="configuration_orders_cp1_delete" class="configuration_orders_button">Delete Personal</button><br><br>
		</div>
		<h3 class="configuration_orders"><a href="#">Orders - Referrals</a></h3>
		<div class="configuration_orders">
			<table id="configuration_orders_ref" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_ref_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_ref_add" class="configuration_orders_button">Add Global</button>
			<button type="button" id="configuration_orders_ref_edit" class="configuration_orders_button">Edit Global</button>
			<button type="button" id="configuration_orders_ref_delete" class="configuration_orders_button">Delete Global</button><br><br>
			<table id="configuration_orders_ref1" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="configuration_orders_ref1_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="configuration_orders_ref1_add" class="configuration_orders_button">Add Personal</button>
			<button type="button" id="configuration_orders_ref1_edit" class="configuration_orders_button">Edit Personal</button>
			<button type="button" id="configuration_orders_ref1_delete" class="configuration_orders_button">Delete Personal</button><br><br>
		</div>
		<h3><a href="#">CPT</a></h3>
		<div>
			Search: <input type="text" size="50" id="search_all_cpt" class="text ui-widget-content ui-corner-all" onkeydown="doSearch(arguments[0]||event)"/><br><br> 
			<table id="cpt_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="cpt_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_cpt">Add CPT Code</button>
			<button type="button" id="edit_cpt">Edit CPT Code</button>
			<button type="button" id="delete_cpt">Delete CPT Code</button>
		</div>
		<h3><a href="#">Patient Forms</a></h3>
		<div>
			<table id="patient_forms_list" class="scroll" cellpadding="0" cellspacing="0"></table>
			<div id="patient_forms_list_pager" class="scroll" style="text-align:center;"></div><br>
			<button type="button" id="add_patient_forms">Add</button>
			<button type="button" id="edit_patient_forms">Edit</button>
			<button type="button" id="delete_patient_forms">Delete</button>
		</div>
	</div>
</div>
<div id="configuration_order" title="">
	<form id="configuration_order_form">
		<input type="hidden" id="configuration_orderslist_table"/>
		<input type="hidden" name="orderslist_id" id="configuration_orderslist_id"/>
		<input type="hidden" name="user_id" id="configuration_user_id"/>
		<input type="hidden" name="orders_category" id="configuration_orders_categrory"/>
		Order:<br><input type="text" name="orders_description" id="configuration_orders_description" style="width:450px" class="text ui-widget-content ui-corner-all"/><br>
		CPT Code (optional):<br><input type="text" name="cpt" id="configuration_cpt" style="width:290px" class="text ui-widget-content ui-corner-all"/><br>
		<div id="configuration_snomed_div">
			SNOMED Code (optional):<br><input type="text" name="snomed" id="configuration_snomed" style="width:290px" class="text ui-widget-content ui-corner-all" placeholder="Type a few letters to search or select from hierarchy."/><br><br>
			SNOMED Database: Click on arrow to expand hierarchy.  Click on item to select code.<br>
			<div id="configuration_snomed_tree" style="height:250px; overflow:auto;"></div>
		</div>
	</form>
</div>
<div id="configuration_cpt_dialog" title="">
	<form id="configuration_cpt_form">
		<input type="hidden" id="configuration_cpt_origin"/>
		<input type="hidden" name="cpt_id" id="configuration_cpt_id"/>
		CPT Code:<br><input type="text" name="cpt" id="configuration_cpt_code" style="width:290px" class="text ui-widget-content ui-corner-all"/><br>
		Description:<br><textarea name="cpt_description" id="configuration_cpt_description" style="width:400px" rows="5" class="text ui-widget-content ui-corner-all"/><br>
		Charge:<br>$<input type="text" name="cpt_charge" id="configuration_charge" style="width:290px" class="text ui-widget-content ui-corner-all"/>
	</form>
</div>
<div id="configuration_patient_forms_dialog" title="">
	<form id="configuration_patient_forms_form">
		<input type="hidden" name="template_id" id="configuration_patient_forms_template_id" value=''/>
		<input type="hidden" name="array" id="configuration_patient_forms_json" value=''/>
		<div style="display:block;float:left;width:310px">
			Form Title:<br><input type="text" name="template_name" id="configuration_patient_forms_title" style="width:290px" class="text ui-widget-content ui-corner-all forms_main"/><br>
			Form Destination to Encounter Element:<br><select name="forms_destination" id="configuration_patient_forms_destination" style="width:290px" class="text ui-widget-content ui-corner-all forms_main"></select><br>
		</div>
		<div style="display:block;float:left">
			Gender<br><select name="sex" id="configuration_patient_forms_gender" style="width:290px" class="text ui-widget-content ui-corner-all forms_main"></select><br>
			Age Group<br><select name="age" id="configuration_patient_forms_age_group" style="width:290px" class="text ui-widget-content ui-corner-all forms_main"></select>
		</div>
	</form>
	<hr class="ui-state-default"/>
	<div style="display:block;float:left;width:310px">
		<strong>Here is what the form will look like!</strong><br>
		Click around the form element to edit.<br>
		<div id="patient_forms_preview_surround" class="ui-corner-all ui-tabs ui-widget ui-widget-content" style="width:290px"><form id="patient_forms_preview" class="ui-widget"></form></div>
		<br><button type="button" id="patient_forms_add_element">Add Element</button>
	</div>
	<div style="display:block;float:left">
		<div id="patient_forms_template_surround_div">
			<button type="button" id="patient_forms_element_save">Save</button><button type="button" id="patient_forms_element_cancel">Cancel</button><button type="button" id="patient_forms_element_delete">Delete Form Element</button><br/>
			<div id="patient_forms_template_div">
				<input type="hidden" id="patient_forms_div_id"/>
				Label:<br><input type="text" id="configuration_patient_forms_label" style="width:290px" class="text ui-widget-content ui-corner-all"/><br>
				Field Type:<br><select id="configuration_patient_forms_fieldtype" style="width:290px" class="text ui-widget-content ui-corner-all"></select><br>
				<div id="patient_forms_template_div_options"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#configuration_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		open: function(event, ui) {
			$("#configuration_accordion").accordion({ heightStyle: "content" });
			var user_type = '<?php echo $user;?>';
			if (user_type == 'assistant') {
				$(".configuration_hpi").hide();
				$(".configuration_pe").hide();
				$("#configuration_accordion").accordion("option", "active", 1);
			}
			if (user_type == 'billing') {
				$(".configuration_hpi").hide();
				$(".configuration_pe").hide();
				$(".configuration_ros").hide();
				$(".configuration_orders").hide();
				$("#configuration_accordion").accordion("option", "active", 6);
			}
			jQuery("#configuration_orders_labs").jqGrid('GridUnload');
			jQuery("#configuration_orders_labs").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Laboratory/Global",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_labs_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Global Laboratory Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_labs_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_labs1").jqGrid('GridUnload');
			jQuery("#configuration_orders_labs1").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Laboratory/User",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_labs1_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Personal Laboratory Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_labs1_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_rad").jqGrid('GridUnload');
			jQuery("#configuration_orders_rad").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Radiology/Global",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_rad_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Global Imaging Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_rad_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_rad1").jqGrid('GridUnload');
			jQuery("#configuration_orders_rad1").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Radiology/User",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_rad1_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Personal Imaging Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_rad1_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_cp").jqGrid('GridUnload');
			jQuery("#configuration_orders_cp").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Cardiopulmonary/Global",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_cp_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Global Cardiopulmonary Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_cp_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_cp1").jqGrid('GridUnload');
			jQuery("#configuration_orders_cp1").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Cardiopulmonary/User",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_cp1_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Personal Cardiopulmonary Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_cp1_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_ref").jqGrid('GridUnload');
			jQuery("#configuration_orders_ref").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Referral/Global",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_ref_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Global Referral Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_ref_pager',{search:false,edit:false,add:false,del:false});
			jQuery("#configuration_orders_ref1").jqGrid('GridUnload');
			jQuery("#configuration_orders_ref1").jqGrid({
				url:"<?php echo site_url('start/orders_list/');?>/Referral/User",
				datatype: "json",
				mtype: "POST",
				colNames:['ID','Group','Category','Description','CPT','SNOMED'],
				colModel:[
					{name:'orderslist_id',index:'orderslist_id',width:1,hidden:true},
					{name:'user_id',index:'user_id',width:1,hidden:true},
					{name:'orders_category',index:'orders_category',width:1,hidden:true},
					{name:'orders_description',index:'orders_description',width:355},
					{name:"cpt",index:"cpt",width:100},
					{name:"snomed",index:"snomed",width:100}
				],
				rowNum:10,
				rowList:[10,20,30],
				pager: jQuery('#configuration_orders_ref1_pager'),
				sortname: 'orders_description',
			 	viewrecords: true,
			 	sortorder: "asc",
			 	caption:"Personal Referral Orders List",
			 	height: "100%",
			 	jsonReader: { repeatitems : false, id: "0" }
			}).navGrid('#configuration_orders_ref1_pager',{search:false,edit:false,add:false,del:false});
		}
	});
	$("#nosh_configuration").click(function() {
		$("#configuration_dialog").dialog('open');
	});
	$("#configuration_order").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'Save': function() {
				var a = $("#configuration_orders_description");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Orders Description");
				if (bValid) {
					var str = $("#configuration_order_form").serialize();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url($url);?>",
						data: str,
						success: function(data){
							$.jGrowl(data);
							var b = $("#configuration_orderslist_table").val();
							jQuery("#" + b).trigger("reloadGrid");
							$("#configuration_order_form").clearForm();
							$("#configuration_order").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_order_form").clearForm();
				$("#configuration_order").dialog('close');
			}
		},
		open: function(event, ui) {
			$.ajax({
				url: "<?php echo site_url('start/check_snomed_extension');?>",
				type: "POST",
				success: function(data){
					if(data =='y'){
						$("#configuration_snomed_div").show();
						$("#configuration_snomed_tree").jstree({
							"plugins" : [ "json_data", "sort", "ui", "themeroller" ],
							"json_data" : {
								"ajax" : {
									"type": 'POST',
									"url": function (node) {
										var nodeId = "";
										var url = "";
										var type1 = $("#configuration_orders_categrory").val();
										if (type1 == "Laboratory") {
											var type = "lab";
										}
										if (type1 == "Radiology") {
											var type = "imaging";
										}
										if (type1 == "Cardiopulmonary") {
											var type = "cp";
										}
										if (node == -1) {
											url = "<?php echo site_url('search/snomed_parent/');?>/" + type;
										} else {
											nodeId = node.attr('id');
											url = "<?php echo site_url('search/snomed_child');?>/" + nodeId;
										}
										return url;
									},
									"success": function (new_data) {
										return new_data;
									}
								}
							},
							"themeroller" : {
								"item" : 'ui-widget-content'
							}
						}).bind("select_node.jstree", function (event, data) {
							$("#configuration_snomed").val(data.rslt.obj.attr("id"));
						});
						$("#configuration_snomed").autocomplete({
								source: function (req, add){
								$.ajax({
									url: "<?php echo site_url('search/snomed');?>/procedure",
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
							minLength: 3
						});
					} else {
						$("#configuration_snomed_div").hide();
					}
				}
			});
			$("#configuration_cpt").autocomplete({
				source: function (req, add){
					$.ajax({
						url: "<?php echo site_url('search/cpt');?>",
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
				minLength: 3
			});
			$("#configuration_orders_description").focus();
		},
		close: function(event, ui) {
			$("#configuration_order_form").clearForm();
			$('#configuration_order').dialog('option', 'title', "");
		}
	});
	$(".configuration_orders_button").button().click(function(){
		var id = $(this).attr("id");
		var parts = id.split('_');
		var parent_id_table = parts[0] + '_' + parts[1] + '_' + parts[2];
		if (parts[2] == 'labs' || parts[2] == 'labs1') {
			var type = "Laboratory";
		}
		if (parts[2] == 'rad' || parts[2] == 'rad1') {
			var type = "Radiology";
		}
		if (parts[2] == 'cp' || parts[2] == 'cp1') {
			var type = "Cardiopulmonary";
		}
		if (parts[2] == 'labs' || parts[2] == 'rad' || parts[2] == 'cp') {
			var group = '0';
		} else {
			var group = "<?php echo $this->session->userdata('user_id');?>";
		}
		if (parts[3] == 'add') {
			$("#configuration_order_form").clearForm();
			$("#configuration_orders_categrory").val(type);
			$("#configuration_orderslist_table").val(parent_id_table);
			$("#configuration_user_id").val(group);
			$('#configuration_order').dialog('open');
			$('#configuration_order').dialog('option', 'title', "Add Order");
		}
		if (parts[3] == 'edit') {
			var item = jQuery("#" + parent_id_table).getGridParam('selrow');
			if(item){
				jQuery("#" + parent_id_table).GridToForm(item,"#configuration_order_form");
				$("#configuration_orderslist_table").val(parent_id_table);
				$('#configuration_order').dialog('open');
				$('#configuration_order').dialog('option', 'title', "Edit Order");
			} else {
				$.jGrowl("Please select order to edit!");
			}
		}
		if (parts[3] == 'delete') {
			var item = jQuery("#" + parent_id_table).getGridParam('selrow');
			if(item){
				if(confirm('Are you sure you want to delete this order?')){
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('start/delete_orders_list');?>",
						data: "orderslist_id=" + item,
						success: function(data){
							$.jGrowl(data);
							jQuery("#" + parent_id_table).trigger("reloadGrid");
						}
					});
				}
			} else {
				$.jGrowl("Please select order to delete!");
			}
		}
	});
	var timeoutHnd;
	function doSearch(ev){ 
		if(timeoutHnd) 
			clearTimeout(timeoutHnd);
			timeoutHnd = setTimeout(gridReload,500);
	}
	function gridReload(){ 
		var mask = jQuery("#search_all_cpt").val();
		jQuery("#cpt_list").setGridParam({url:"<?php echo site_url('start/cpt_list');?>/"+mask,page:1}).trigger("reloadGrid");
	}
	jQuery("#cpt_list").jqGrid({
		url:"<?php echo site_url('start/cpt_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','CPT Code','Description','Charge'],
		colModel:[
			{name:'cpt_id',index:'cpt_id',width:1,hidden:true},
			{name:'cpt',index:'cpt',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
			{name:'cpt_description',index:'cpt_description',width:350,editable:true,editrules:{required:true},edittype:"textarea",editoptions:{rows:"4",cols:"50"},formoptions:{elmsuffix:"(*)"}},
			{name:'cpt_charge',index:'cpt_charge',width:100,editable:true,editrules:{required:true},formoptions:{elmsuffix:"(*)"}},
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#cpt_list_pager'),
		sortname: 'cpt',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"CPT Codes",
	 	emptyrecords:"No CPT codes",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#cpt_list_pager',{edit:false,add:false,del:false});
	$("#add_cpt").button().click(function(){
		$("#configuration_cpt_form").clearForm();
		$('#configuration_cpt_dialog').dialog('open');
		$('#configuration_cpt_dialog').dialog('option', 'title', "Add CPT Code");
	});
	$("#edit_cpt").button().click(function(){
		var item = jQuery("#cpt_list").getGridParam('selrow');
		if(item){ 
			jQuery("#cpt_list").GridToForm(item,"#configuration_cpt_form");
			$('#configuration_cpt_dialog').dialog('open');
			$('#configuration_cpt_dialog').dialog('option', 'title', "Edit CPT Code");
		} else {
			$.jGrowl("Please select CPT code to edit!");
		}
	});
	$("#delete_cpt").button().click(function(){
		var item = jQuery("#cpt_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/delete_cpt');?>",
				data: "cpt_id=" + item,
				success: function(data){
					jQuery("#cpt_list").trigger("reloadGrid");
				}
			});
		} else {
			$.jGrowl("Please select CPT code to delete!");
		}
	});
	$("#configuration_cpt_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 300, 
		width: 800, 
		draggable: false,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		buttons: {
			'Save': function() {
				var a = $("#configuration_cpt_code");
				var b = $("#configuration_cpt_description");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"CPT Code");
				bValid = bValid && checkEmpty(b,"Description");
				if (bValid) {
					var str = $("#configuration_cpt_form").serialize();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('start/edit_cpt_list');?>",
						data: str,
						dataType: 'json',
						success: function(data){
							$.jGrowl(data.message);
							jQuery("#cpt_list").trigger("reloadGrid");
							var origin = $("#configuration_cpt_origin").val();
							var cpt = $("#configuration_cpt_code").val();
							if (origin != "") {
								var parts = origin.split('_');
								if (parts[0] == 'billing') {
									if (parts[1] == 'cpt') {
										$('#' + origin + "_charge").val(data.charge);
									}
									if (parts[1] == 'cpt1') {
										$('#' + origin + "_charge1").val(data.charge);
									}
								}
								$('#' + origin).val(cpt);
							}
							$("#configuration_cpt_form").clearForm();
							$("#configuration_cpt_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				var origin = $("#configuration_cpt_origin").val();
				var cpt = $("#configuration_cpt_code").val();
				if (origin != "") {
					$('#' + origin).val("");
				}
				$("#configuration_cpt_form").clearForm();
				$("#configuration_cpt_dialog").dialog('close');
			}
		},
		open: function (event, ui) {
			$("#configuration_cpt").focus();
		},
		close: function (event, ui) {
			$("#configuration_cpt_form").clearForm();
			$('#configuration_cpt_dialog').dialog('option', 'title', "");
		}
	});
	jQuery("#patient_forms_list").jqGrid({
		url:"<?php echo site_url('start/patient_forms_list');?>",
		datatype: "json",
		mtype: "POST",
		colNames:['ID','Form','Gender','Group','Age'],
		colModel:[
			{name:'template_id',index:'template_id',width:1,hidden:true},
			{name:'template_name',index:'template_name',width:300},
			{name:'sex',index:'sex',width:100},
			{name:'group',index:'group',width:100},
			{name:'age',index:'age',width:1,hidden:true}
		],
		rowNum:10,
		rowList:[10,20,30],
		pager: jQuery('#patient_forms_list_pager'),
		sortname: 'template_id',
	 	viewrecords: true,
	 	sortorder: "asc",
	 	caption:"Patient Forms",
	 	emptyrecords:"No forms",
	 	height: "100%",
	 	jsonReader: { repeatitems : false, id: "0" }
	}).navGrid('#patient_forms_list_pager',{edit:false,add:false,del:false});
	$("#add_patient_forms").button().click(function(){
		$("#configuration_patient_forms_form").clearForm();
		$("#configuration_patient_forms_gender").val('b');
		$("#configuration_patient_forms_age_group").val('');
		$("#configuration_patient_forms_destination").val('');
		$('#configuration_patient_forms_dialog').dialog('open');
		$('#configuration_patient_forms_dialog').dialog('option', 'title', "Add Patient Form");
	});
	$("#edit_patient_forms").button().click(function(){
		var item = jQuery("#patient_forms_list").getGridParam('selrow');
		if(item){ 
			jQuery("#patient_forms_list").GridToForm(item,"#configuration_patient_forms_form");
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/get_patient_forms');?>",
				data: "template_id=" + item,
				success: function(data){
					$("#configuration_patient_forms_json").val(data);
					$('#configuration_patient_forms_dialog').dialog('open');
					$('#configuration_patient_forms_dialog').dialog('option', 'title', "Edit Patient Form");
				}
			});
		} else {
			$.jGrowl("Please select form to edit!");
		}
	});
	$("#delete_patient_forms").button().click(function(){
		var item = jQuery("#patient_forms_list").getGridParam('selrow');
		if(item){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('start/delete_patient_forms');?>",
				data: "template_id=" + item,
				success: function(data){
					jQuery("#patient_forms_list").trigger("reloadGrid");
					$.jGrowl(data);
				}
			});
		} else {
			$.jGrowl("Please select form to delete!");
		}
	});
	$("#configuration_patient_forms_gender").addOption({"b":"Both","m":"Male","f":"Female"});
	$("#configuration_patient_forms_age_group").addOption({"":"All","adult":"Adult","child":"Child"});
	$("#configuration_patient_forms_destination").addOption({"":"Select Encounter/Chart Destination.","HPI":"History of Present Illness","PMH":"Past Medical History","PSH":"Past Surgical History","FH":"Family History","SH":"Social History"});
	$("#configuration_patient_forms_fieldtype").addOption({"":"Select Field Type.","text":"Text","radio":"Radio Buttons - User can select only one option.","checkbox":"Checkbox - User can select multiple options.","select":"Drop down list"}).on("change", function(){
		var a = $(this).val();
		if (a == 'radio' || a == 'checkbox' || a == 'select') {
			$("#patient_forms_template_div_options").html('Option:<br><input type="text" id="configuration_patient_forms_option_1" style="width:290px" class="text ui-widget-content ui-corner-all patient_forms_option"/><button type="button" id="configuration_patient_forms_add_option">Add</button><br>');
			$("#configuration_patient_forms_add_option").button({icons: {primary: "ui-icon-plus"}}).on("click",function() {
				var a = $(".patient_forms_option:last").attr("id");
				var a1 = a.split("_");
				var count = parseInt(a1[4]) + 1;
				$("#patient_forms_template_div_options").append('<div>Option:<br><input type="text" id="configuration_patient_forms_option_' + count + '" style="width:290px" class="text ui-widget-content ui-corner-all patient_forms_option"/><button type="button" id="configuration_patient_forms_option_' + count +'_remove" class="patient_forms_remove_option">Remove</button><br></div>');
				$('#configuration_patient_forms_option_' + count).focus();
				$(".patient_forms_remove_option").button({icons: {primary: "ui-icon-minus"}}).on("click",function() {
					$(this).parent().remove();
				});
			});
		} else {
			$("#patient_forms_template_div_options").html('');
		}
	});
	$("#patient_forms_element_save").button().click(function(){
		var json_flat = $("#configuration_patient_forms_json").val();
		var json_object = JSON.parse(json_flat);
		var div_id = $("#patient_forms_div_id").val();
		if (div_id != '') {
			for (var i = 0; i < Object.size(json_object.html); i++) {
				var a = json_object.html[i].id;
				if (a == div_id) {
					json_object.html[i].html[0].html = $("#configuration_patient_forms_label").val();
					if ($("#configuration_patient_forms_fieldtype").val() == "radio" || $("#configuration_patient_forms_fieldtype").val() == "checkbox") {
						var h = 2;
						var g = h-2;
						$(".patient_forms_option").each(function(){
							if (json_object.html[i].html[h]) {
								delete json_object.html[i].html[h];
								json_object.html[i].html[h] = new Object();
							} else {
								json_object.html[i].html[h] = new Object();
							}
							json_object.html[i].html[h].type = $("#configuration_patient_forms_fieldtype").val();
							json_object.html[i].html[h].id = $("#patient_forms_div_id").val() + "_" + $("#configuration_patient_forms_fieldtype").val() + "_" + g;
							json_object.html[i].html[h].name = $("#patient_forms_div_id").val();
							json_object.html[i].html[h].value = $("#configuration_patient_forms_label").val() + ": " + $(this).val();
							json_object.html[i].html[h].caption = $(this).val();
							h++;
							g++;
						});
						json_object.html[i].class = "patient_form_div patient_form_buttonset";
					} else {
						json_object.html[i].html[2].type = $("#configuration_patient_forms_fieldtype").val();
						json_object.html[i].html[2].id = $("#patient_forms_div_id").val() + "_" + $("#configuration_patient_forms_fieldtype").val();
						json_object.html[i].html[2].name = $("#patient_forms_div_id").val();
						if ($("#configuration_patient_forms_fieldtype").val() == "select") {
							if ($("#configuration_patient_forms_option_1").val() != "") {
								if (json_object.html[i].html[2]['options']) {
									delete json_object.html[i].html[2]['options'];
									json_object.html[i].html[2].options = new Object();
								}
								$(".patient_forms_option").each(function(){
									var value = $(this).val();
									var key = $("#configuration_patient_forms_label").val() + ": " + $(this).val();
									json_object.html[i].html[2]['options'][key] = value;
								});
								json_object.html[i].class = "patient_form_div";
							}
						} else {
							json_object.html[i].class = "patient_form_div patient_form_text";
						}
					}
				}
			}
		} else {
			var j = Object.size(json_object.html);
			var l = j-3;
			if ($("#configuration_patient_forms_fieldtype").val() == 'text') {
				var k = "patient_form_div patient_form_text";
				json_object.html[j] = {"type":"div","class":"patient_form_div patient_form_text","id":"patient_form_div"+l,"html":[{"type":"span","id":"patient_form_div"+l+"_label","html":$("#configuration_patient_forms_label").val()},{"type":"br"},{"type":$("#configuration_patient_forms_fieldtype").val(),"id":"patient_form_div"+l+"_"+$("#configuration_patient_forms_fieldtype").val(),"name":"patient_form_div"+l,"value":""}]};
			}
			if ($("#configuration_patient_forms_fieldtype").val() == 'radio' || $("#configuration_patient_forms_fieldtype").val() == 'checkbox') {
				var m = 2;
				var n = m-2;
				json_object.html[j] = {"type":"div","class":"patient_form_div patient_form_buttonset","id":"patient_form_div"+l,"html":[{"type":"span","id":"patient_form_div"+l+"_label","html":$("#configuration_patient_forms_label").val()},{"type":"br"}]};
				$(".patient_forms_option").each(function(){
					json_object.html[j].html[m] = {"type":$("#configuration_patient_forms_fieldtype").val(),"id":"patient_form_div"+l+"_"+$("#configuration_patient_forms_fieldtype").val()+"_"+n,"name":"patient_form_div"+l,"value":$("#configuration_patient_forms_label").val()+": "+$(this).val(),"caption":$(this).val()};
					m++;
					n++;
				});
			}
			if ($("#configuration_patient_forms_fieldtype").val() == 'select') {
				json_object.html[j] = {"type":"div","class":"patient_form_div","id":"patient_form_div"+l,"html":[{"type":"span","id":"patient_form_div"+l+"_label","html":$("#configuration_patient_forms_label").val()},{"type":"br"},{"type":$("#configuration_patient_forms_fieldtype").val(),"id":"patient_form_div"+l+"_"+ $("#configuration_patient_forms_fieldtype").val(),"name":"patient_form_div"+l,"options":{}}]};
				$(".patient_forms_option").each(function(){
					var value = $(this).val();
					var key = $("#configuration_patient_forms_label").val() + ": " + $(this).val();
					json_object.html[j].html[2].options[key] = value;
				});
			}
		}
		var json_flat1 = JSON.stringify(json_object);
		$("#configuration_patient_forms_json").val(json_flat1);
		preview_form();
		$("#patient_forms_template_div").clearDiv();
		$("#patient_forms_template_div_options").html('');
		$("#patient_forms_template_surround_div").hide();
	});
	$("#patient_forms_element_cancel").button().click(function(){
		$("#patient_forms_template_div").clearDiv();
		$("#patient_forms_template_div_options").html('');
		$("#patient_forms_template_surround_div").hide();
		$(".patient_form_div").removeClass("ui-state-error");
	});
	$("#patient_forms_element_delete").button().click(function(){
		var json_flat = $("#configuration_patient_forms_json").val();
		var json_object = JSON.parse(json_flat);
		var json_array = [json_object];
		var div_id = $("#patient_forms_div_id").val();
		for (var i = 0; i < json_array[0]['html'].length; i++) {
			var a = json_array[0]['html'][i].id;
			if (a == div_id) {
				json_array[0]['html'].splice(i,1);
			}
		}
		var json_flat1 = JSON.stringify(json_array[0]);
		$("#configuration_patient_forms_json").val(json_flat1);
		preview_form();
		$("#patient_forms_template_div").clearDiv();
		$("#patient_forms_template_div_options").html('');
		$("#patient_forms_template_surround_div").hide();
	});
	$("#configuration_patient_forms_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		draggable: false,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 50
		},
		open: function() {
			$("#configuration_patient_forms_title").focus();
			$("#patient_forms_template_surround_div").hide();
			preview_form();
		},
		buttons: {
			'Save': function() {
				var a = $("#configuration_patient_forms_title");
				var b = $("#configuration_patient_forms_destination");
				var c = $("#configuration_patient_forms_json");
				var bValid = true;
				bValid = bValid && checkEmpty(a,"Form Title");
				bValid = bValid && checkEmpty(b,"Form Destination");
				bValid = bValid && checkEmpty(c,"Form");
				if (bValid) {
					var json_flat = $("#configuration_patient_forms_json").val();
					var json_object = JSON.parse(json_flat);
					json_object.html[2].value = $("#configuration_patient_forms_title").val();
					json_object.html[3].value = $("#configuration_patient_forms_destination").val();
					var json_flat1 = JSON.stringify(json_object);
					$("#configuration_patient_forms_json").val(json_flat1);
					var str = $("#configuration_patient_forms_form").serialize();
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('start/save_patient_form');?>/global",
						data: str,
						success: function(data){
							$.jGrowl(data);
							jQuery("#patient_forms_list").trigger("reloadGrid");
							$("#configuration_patient_forms_form").clearForm();
							$("#configuration_patient_forms_dialog").dialog('close');
						}
					});
				}
			},
			Cancel: function() {
				$("#configuration_patient_forms_form").clearForm();
				$("#configuration_patient_forms_dialog").dialog('close');
			}
		},
		close: function (event, ui) {
			$("#configuration_patient_forms_form").clearForm();
			$('#configuration_patient_forms_dialog').dialog('option', 'title', "");
		}
	});
	$("#patient_forms_add_element").button({icons: {primary: "ui-icon-plus"}}).click(function() {
		if($("#patient_forms_template_surround_div").is(":hidden")) {
			$("#patient_forms_template_div").clearDiv();
			$("#patient_forms_template_div_options").html('');
			$("#configuration_patient_forms_fieldtype").val('');
			$("#patient_forms_template_surround_div").show();
		} else {
			$.jGrowl("Finish editing current form element!");
		}
	});
	function preview_form() {
		if ($("#configuration_patient_forms_json").val() == '') {
			var default_json = '{"html":[{"type":"hidden","class":"patient_form_hidden","value":"","id":"form_template_id","name":"template_id"},{"type":"hidden","class":"patient_form_hidden","value":"","id":"form_forms_content","name":"forms_content"},{"type":"hidden","class":"patient_form_hidden","value":"","id":"form_forms_title","name":"forms_title"},{"type":"hidden","class":"patient_form_hidden","value":"","id":"form_forms_destination","name":"forms_destination"},{"type":"div","class":"patient_form_div patient_form_buttonset","id":"patient_form_div1","html":[{"type":"span","id":"patient_form_div1_label","html":"Radio Button Question"},{"type":"br"},{"type":"radio","id":"patient_form_div1_radio_0","name":"patient_form_div1","value":"Radio Button Question: No","caption":"No"},{"type":"radio","id":"patient_form_div1_radio_1","name":"patient_form_div1","value":"Radio Button Question: Yes","caption":"Yes"}]},{"type":"div","class":"patient_form_div patient_form_text","id":"patient_form_div2","html":[{"type":"span","id":"patient_form_div2_label","html":"Text Question"},{"type":"br"},{"type":"text","id":"patient_form_div2_text","name":"patient_form_div2","value":""}]},{"type":"div","class":"patient_form_div","id":"patient_form_div3","html":[{"type":"span","id":"patient_form_div3_label","html":"Select List Question"},{"type":"br"},{"type":"select","id":"patient_form_div3_select","name":"patient_form_div3","options":{"Select List Question: No":"No","Select List Question: Yes":"Yes"}}]}]}';
			$("#configuration_patient_forms_json").val(default_json);
		} else {
			var default_json = $("#configuration_patient_forms_json").val();
		}
		var default_json_object = JSON.parse(default_json);
		$("#patient_forms_preview").html('');
		$("#patient_forms_preview").dform(default_json_object);
		$(".patient_form_buttonset").buttonset();
		$('.patient_form_text input[type="text"]').css("width","280px");
		$('.patient_form_div select').addClass("text ui-widget-content ui-corner-all");
		if (default_json_object.html[2].value != "") {
			$("#configuration_patient_forms_title").val(default_json_object.html[2].value);
		}
		if (default_json_object.html[3].value != "") {
			$("#configuration_patient_forms_destination").val(default_json_object.html[3].value);
		}
		$(".patient_form_div").css("padding","5px").on("click", function(){
			if($("#patient_forms_template_surround_div").is(":hidden")) {
				$(this).addClass("ui-state-error");
				$(this).siblings().removeClass("ui-state-error");
				var div_id = $(this).attr('id');
				$("#patient_forms_div_id").val(div_id);
				var json_flat = $("#configuration_patient_forms_json").val();
				var json_object = JSON.parse(json_flat);
				for (var i = 0; i < Object.size(json_object.html); i++) {
					var a = json_object.html[i].id;
					if (a == div_id) {
						$("#configuration_patient_forms_label").val(json_object.html[i].html[0].html);
						$("#configuration_patient_forms_fieldtype").val(json_object.html[i].html[2].type);
						if (json_object.html[i].html[2].type != "text") {
							$("#patient_forms_template_div_options").html('Option:<br><input type="text" id="configuration_patient_forms_option_1" style="width:290px" class="text ui-widget-content ui-corner-all patient_forms_option"/><button type="button" id="configuration_patient_forms_add_option">Add</button><br>');
							$("#configuration_patient_forms_add_option").button({icons: {primary: "ui-icon-plus"}}).on("click", function() {
								var a = $(".patient_forms_option:last").attr("id");
								var a1 = a.split("_");
								var count = parseInt(a1[4]) + 1;
								$("#patient_forms_template_div_options").append('<div>Option:<br><input type="text" id="configuration_patient_forms_option_' + count + '" style="width:290px" class="text ui-widget-content ui-corner-all patient_forms_option"/><button type="button" id="configuration_patient_forms_option_' + count +'_remove" class="patient_forms_remove_option">Remove</button><br></div>');
								$("#configuration_patient_forms_option_" + count).focus();
								$(".patient_forms_remove_option").button({icons: {primary: "ui-icon-minus"}}).on("click",function() {
									$(this).parent().remove();
								});
							});
							if (json_object.html[i].html[2].type == "select") {
								var j = 1;
								$.each(json_object.html[i].html[2].options, function(k, v) {
									if (j > 1) {
										$("#patient_forms_template_div_options").append('<div>Option:<br><input type="text" id="configuration_patient_forms_option_' + j + '" style="width:290px" class="text ui-widget-content ui-corner-all patient_forms_option"/><button type="button" id="configuration_patient_forms_option_' + j +'_remove" class="patient_forms_remove_option">Remove</button><br></div>');
										$(".patient_forms_remove_option").button({icons: {primary: "ui-icon-minus"}}).on("click",function() {
											$(this).parent().remove();
										});
									}
									$("#configuration_patient_forms_option_" + j).val(v);
									j++;
								});
							} else {
								var l = 1;
								for (var k = 2; k < Object.size(json_object.html[i].html); k++) {
									if (l > 1) {
										$("#patient_forms_template_div_options").append('Option:<br><input type="text" id="configuration_patient_forms_option_' + l + '" style="width:290px" class="text ui-widget-content ui-corner-all patient_forms_option"/><br>');
									}
										$("#configuration_patient_forms_option_" + l).val(json_object.html[i].html[k].caption);
									l++;
								}
							}
						} else {
							$("#patient_forms_template_div_options").html('');
						}
					}
				}
				$("#patient_forms_template_surround_div").show();
			} else {
				$.jGrowl("Finish editing current form element!");
			}
		});
	}
	Object.size = function(obj) {
		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) size++;
		}
		return size;
	};
</script>
