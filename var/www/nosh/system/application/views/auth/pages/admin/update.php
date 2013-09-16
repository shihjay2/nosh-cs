<script type="text/javascript">
$(document).ready(function() {
	$("#add_medlist_file").click(function(){
		var id = '1';
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/medlist_update');?>",
			data: "id=" + id,
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_supplement_file").click(function(){
		var id = '1';
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/supplements_update');?>",
			data: "id=" + id,
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
	$("#add_icd_file").click(function(){
		var id = '1';
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/icd_update');?>",
			data: "id=" + id,
			success: function(data){
				$.jGrowl(data.result);
			}
		});
	});
	$("#add_cpt_upload").button();
	$("#add_npi_upload").button();
	$("#add_cvx_file").click(function(){
		var id = '1';
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('admin/setup/cvx_update');?>",
			data: "id=" + id,
			success: function(data){
				$.jGrowl(data);
			}
		});
	});
});
</script>
<img id="loading" src="<?php echo base_url().'images/indicator.gif';?>" style="display:none;">
<fieldset class="ui-corner-all">
	<legend>Medication List Update</legend>
	Downloads .zip file from <a href="http://www.fda.gov/Drugs/InformationOnDrugs/ucm142438.htm#download" target="_blank">the FDA NDC databases.</a><br><br>
	<input type="button" id="add_medlist_file" value="Update Medication List Database" class="ui-button ui-state-default ui-corner-all"/>
</fieldset><br>
<fieldset class="ui-corner-all">
	<legend>Supplement Update</legend>
	Imports data from <a href="http://www.nlm.nih.gov/medlineplus/druginfo/herb_All.html" target="_blank">the U.S. National Library of Medicine.</a><br><br>
	<input type="button" id="add_supplement_file" value="Update Supplement List Database" class="ui-button ui-state-default ui-corner-all"/>
</fieldset><br>
<fieldset class="ui-corner-all">
	<legend>ICD Database Update</legend>
	Scrape data from <a href="http://www.icd9data.com" target="_blank">www.icd9data.com</a>:<br>
	Imports data into database.<br><br>
	<input type="button" id="add_icd_file" value="Update ICD Database" class="ui-button ui-state-default ui-corner-all"/>
</fieldset><br>
<fieldset class="ui-corner-all">
	<legend>CPT Update/Upgrade</legend>
	Obtain the AMA CPT Data File disk that you have purchased.<br>
	Import the LONGULT.TXT file into database here...<br><br>
	<form id="add_cpt_form" action="<?php echo site_url('admin/setup/cpt_update');?>" method="post" enctype="multipart/form-data">
		<span id="add_cpt_span"><input type="file" name="fileToUpload" id="fileToUpload"> <input type="submit" id="add_cpt_upload" value="Upload"></span>
	</form>
</fieldset><br>
<fieldset class="ui-corner-all">
	<legend>NPI Taxonomy List Update</legend>
	Download .csv file from <a href="http://www.nucc.org/" target="_blank">the National Uniform Claim Committee website and click Code Sets, then Provider Taxonomy, then CSV.</a><br>
	Import the new .csv file into database here...<br><br>
	<form id="add_npi_form" action="<?php echo site_url('admin/setup/npi_update');?>" method="post" enctype="multipart/form-data">
		<span id="add_npi_span"><input type="file" name="fileToUpload1" id="fileToUpload1"> <input type="submit" id="add_npi_upload" value="Upload"></span>
	</form>
</fieldset><br>
<fieldset class="ui-corner-all">
	<legend>CVX Update</legend>
	Imports data from <a href="http://www2a.cdc.gov/vaccines/iis/iisstandards/XML.asp?rpt=cvx" target="_blank">the CDC website.</a><br><br>
	<input type="button" id="add_cvx_file" value="Update CVX Database" class="ui-button ui-state-default ui-corner-all"/>
</fieldset>

