$(document).ready(function(){
	$('#searchpt').autocomplete('<?php echo site_url("search");?>', {
		dataType:"json",
		formatItem: function(data,i,max,value,term){
			return value;
		},
		parse: function(data){
			var array = new Array();
			for(var i=0;i<data.length;i++){
				var tempValue = data[i].lastname + ", " + data[i].firstname + " (id: " + data[i].pid + ")";
				var tempResult = data[i].lastname + ", " + data[i].firstname + " (id: " + data[i].pid + ")";
				array[array.length] = { data:data[i], value: tempValue, result: tempResult};
			}
			return array;
		},
	});
	
	$("#searchpt").result(function(event, data, formatted) {
		$("#searchresult").html( !data ? "-1" : "" + data.pid);
    });
}

function openChartAssistant(){
	var pid = document.getElementById('searchresult');
	self.location = "<?php echo site_url('assistant/chartmenu/');?>"+pid;
}

function openChartProvider(){
	var pid = document.getElementById('searchresult');
	self.location = "<?php echo site_url('provider/chartmenu/');?>"+pid;
}

function openChartBiller(){
	var pid = document.getElementById('searchresult');
	self.location = "<?php echo site_url('biller/chartmenu/');?>"+pid;
}

function openNewPatientAssistant(){
	self.location = "<?php echo site_url('assistant/newpatient/');?>";
}

function openNewPatientProvider(){
	self.location = "<?php echo site_url('provider/newpatient/');?>";
}

function openNewPatientBiller(){
	self.location = "<?php echo site_url('biller/newpatient/');?>";
}
