<script type="text/javascript">
	$(function() {
		$("#togglebox_hpi").toggleboxes();
	});
</script>
<div id="encountertabs">
<form method=post action="<?php echo site_url('provider/encounter/hpi_save');?>" id="hpi">
<input type="button" type="submit" id="save_encounter" value="Save" class="ui-button ui-state-default ui-corner-all"> &nbsp; 
<input type="button" id="cancel_encounter" value="Cancel" class="ui-button ui-state-default ui-corner-all"> &nbsp; 
<br>

<div id="togglebox_hpi">
<!-- Start Cut -->
<div>
<h3><a href="#hpi_1">Generic HPI</a></h3>
<div id="hpi_1">
<table style="text-align: left; width: 731px; height: 32px;" border="0" cellpadding="1" cellspacing="1">
<tbody>
<tr>
<td style="vertical-align: top; width: 307px;">
<input name="gen_set" value="<?php echo $p_age['age']; ?>-old <?php echo $p_age['gender']; ?> with the following concerns.  " onclick="radioselect(this.form, 'gen_set', 'hpi_gen')" type="checkbox"> &nbsp;Standard phrase<br>
<input name="gen_location" value="Location: " onclick="radioselect(this.form, 'gen_location', 'hpi_gen')" type="checkbox"> &nbsp;Location<br>
<input name="gen_duration" value="Duration: " onclick="radioselect(this.form, 'gen_duration', 'hpi_gen')" type="checkbox"> &nbsp;Duration<br>
<input name="gen_modify" value="Modifying factors: " onclick="radioselect(this.form, 'gen_modify', 'hpi_gen')" type="checkbox"> &nbsp;Modifying factors<br>
</td>
<td style="text-align: center; vertical-align: top; width: 404px;"><span style="font-weight: bold;">Generic HPI Preview</span><br>
<textarea cols="40" rows="10" name="hpi_gen" id="hpi_gen" value="<?php if ($hpi->hpi_gen != '') {echo $hpi->hpi_gen;}?>"></textarea><br><input type="button" name="gen_reset" value="Reset" onclick="clearthis(this.form, 'hpi_1', 'hpi_gen')">
</td>
</tr>
</tbody>
</table>
</div>
</div>

<!-- Start Cut -->
<div>
<h3><a href="#hpi_2">Asthma</a></h3>
<div id="hpi_2">
<table style="text-align: left; width: 731px; height: 32px;" border="0" cellpadding="1" cellspacing="1">
<tbody>
<tr>
<td style="vertical-align: top; width: 307px;">
<input name="asthma_set" value="<?php echo $p_age['age']; ?>-old <?php echo $p_age['asthmader']; ?> with asthma here for followup.  " onclick="radioselect(this.form, 'asthma_set', 'hpi_asthma')" type="checkbox"> &nbsp;Standard phrase<br>
<input name="asthma_frequency" value="Frequency of attacks/week: " onclick="radioselect(this.form, 'asthma_frequency', 'hpi_asthma')" type="checkbox"> &nbsp;Frequency of attacks/week<br>
<input name="asthma_time" value="Time of usual attacks: " onclick="radioselect(this.form, 'asthma_time', 'hpi_asthma')" type="checkbox"> &nbsp;Time of usual attacks<br>
<input name="asthma_er" value="Number of ER visits or hospitalizations: " onclick="radioselect(this.form, 'asthma_er', 'hpi_asthma')" type="checkbox"> &nbsp;Number of ER visits or hospitalizations<br>
<input name="asthma_missed" value="Number of school/work days missed: " onclick="radioselect(this.form, 'asthma_missed', 'hpi_asthma')" type="checkbox"> &nbsp;Number of school/work days missed<br>
<input name="asthma_awaken" value="Nocturnal awakenings: " onclick="radioselect(this.form, 'asthma_awaken', 'hpi_asthma')" type="checkbox"> &nbsp;Nocturnal awakenings<br>
<input name="asthma_restriction" value="Restriction of activities: " onclick="radioselect(this.form, 'asthma_restriction', 'hpi_asthma')" type="checkbox"> &nbsp;Restriction of activities<br>
<input name="asthma_infection" value="Recent infections: " onclick="radioselect(this.form, 'asthma_infection', 'hpi_asthma')" type="checkbox"> &nbsp;Recent infections<br>
<input name="asthma_environment" value="Environmental controls and asthma triggers: " onclick="radioselect(this.form, 'asthma_environment', 'hpi_asthma')" type="checkbox"> &nbsp;Environmental controls and asthma triggers<br>
</td>
<td style="text-align: center; vertical-align: top; width: 404px;"><span style="font-weight: bold;">Asthma HPI Preview</span><br>
<textarea cols="40" rows="6" name="hpi_asthma" id="hpi_asthma" value="<?php if ($hpi->hpi_asthma != '') {echo $hpi->hpi_asthma;}?>"></textarea><br><input type="button" name="gen_reset" value="Reset" onclick="clearthis(this.form, 'hpi_2', 'hpi_asthma')">
</td>
</tr>
</tbody>
</table>
</div>
</div>

<div>
<h3><a href="#hpi_3">Chronic Pain</a></h3>
<div id="hpi_3">
<table style="text-align: left; width: 731px; height: 32px;" border="0" cellpadding="1" cellspacing="1">
<tbody>
<tr>
<td style="vertical-align: top; width: 307px;">
<input name="pain_set" value="<?php echo $p_age['age']; ?>-old <?php echo $p_age['Chronic Painder']; ?> with chronic nonmalignant pain here for followup.  " onclick="radioselect(this.form, 'pain_set', 'hpi_pain')" type="checkbox"> &nbsp;Standard phrase<br>
<input name="pain_dx" value="Diagnosis: " onclick="radioselect(this.form, 'pain_dx', 'hpi_pain')" type="checkbox"> &nbsp;Diagnosis<br>
<input name="pain_meds" value="Side effect from medications: " onclick="radioselect(this.form, 'pain_meds', 'hpi_pain')" type="checkbox"> &nbsp;Side effects from medications<br>
<input name="pain_tx" value="Other treatments: " onclick="radioselect(this.form, 'pain_tx', 'hpi_pain')" type="checkbox"> &nbsp;Other treatments<br>
<input name="pain_scale" value="Pain scale (0-10): " onclick="radioselect(this.form, 'pain_scale', 'hpi_pain')" type="checkbox"> &nbsp;Pain scale (0-10)<br>
<input name="pain_relief" value="Relief (% improvement) since last visit: " onclick="radioselect(this.form, 'pain_relief', 'hpi_pain')" type="checkbox"> &nbsp;Relief (% improvement) since last visit<br>
<input name="pain_activity" value="General activity: " onclick="radioselect(this.form, 'pain_activity', 'hpi_pain')" type="checkbox"> &nbsp;General activity<br>
<input name="pain_interactions" value="Interactions with other people: " onclick="radioselect(this.form, 'pain_interactions', 'hpi_pain')" type="checkbox"> &nbsp;Interactions with other people<br>
<input name="pain_mood" value="Mood: " onclick="radioselect(this.form, 'pain_mood', 'hpi_pain')" type="checkbox"> &nbsp;Mood<br>
<input name="pain_sleep" value="Sleep: " onclick="radioselect(this.form, 'pain_sleep', 'hpi_pain')" type="checkbox"> &nbsp;Sleep<br>
<input name="pain_work" value="Ability to work: " onclick="radioselect(this.form, 'pain_work', 'hpi_pain')" type="checkbox"> &nbsp;Ability to work<br>
<input name="pain_life" value="Enjoyment of life: " onclick="radioselect(this.form, 'pain_life', 'hpi_pain')" type="checkbox"> &nbsp;Enjoyment of life<br>
</td>
<td style="text-align: center; vertical-align: top; width: 404px;"><span style="font-weight: bold;">Chronic Pain HPI Preview</span><br>
<textarea cols="40" rows="8" name="hpi_pain" id="hpi_pain" value="<?php if ($hpi->hpi_pain != '') {echo $hpi->hpi_pain;}?>"></textarea><br><input type="button" name="gen_reset" value="Reset" onclick="clearthis(this.form, 'hpi_3', 'hpi_pain')">
</td>
</tr>
</tbody>
</table>
</div>
</div>

<div>
<h3><a href="#hpi_4">MVA</a></h3>
<div id="hpi_4">
<table style="text-align: left; width: 731px; height: 32px;" border="0" cellpadding="1" cellspacing="1">
<tbody>
<tr>
<td style="vertical-align: top; width: 307px;">
<input name="mva_set" value="<?php echo $p_age['age']; ?>-old <?php echo $p_age['Chronic mvader']; ?> here with concerns following a motor vehicle accident.  " onclick="radioselect(this.form, 'mva_set', 'hpi_mva')" type="checkbox"> &nbsp;Standard phrase<br>
<input name="mva_date" value="Date of injury: " onclick="radioselect(this.form, 'mva_date', 'hpi_mva')" type="checkbox"> &nbsp;Date of injury<br>
<input name="mva_description" value="Description of accident: " onclick="radioselect(this.form, 'mva_description', 'hpi_mva')" type="checkbox"> &nbsp;Description of accident<br>
<input name="mva_tx" value="Treatments: " onclick="radioselect(this.form, 'mva_tx', 'hpi_mva')" type="checkbox"> &nbsp;Treatments<br>
<input name="mva_scale" value="Pain scale (0-10): " onclick="radioselect(this.form, 'mva_scale', 'hpi_mva')" type="checkbox"> &nbsp;Pain scale (0-10)<br>
<input name="mva_relief" value="Relief (% improvement) since last visit: " onclick="radioselect(this.form, 'mva_relief', 'hpi_mva')" type="checkbox"> &nbsp;Relief (% improvement) since last visit<br>
<input name="mva_activity" value="General activity: " onclick="radioselect(this.form, 'mva_activity', 'hpi_mva')" type="checkbox"> &nbsp;General activity<br>
<input name="mva_work" value="Ability to work: " onclick="radioselect(this.form, 'mva_work', 'hpi_mva')" type="checkbox"> &nbsp;Ability to work<br>
</td>
<td style="text-align: center; vertical-align: top; width: 404px;"><span style="font-weight: bold;">MVA HPI Preview</span><br>
<textarea cols="40" rows="6" name="hpi_mva" id="hpi_mva" value="<?php if ($hpi->hpi_mva != '') {echo $hpi->hpi_mva;}?>"></textarea><br><input type="button" name="gen_reset" value="Reset" onclick="clearthis(this.form, 'hpi_4', 'hpi_mva')">
</td>
</tr>
</tbody>
</table>
</div>
</div>

<div>
<h3><a href="#hpi_5">Sports Physical</a></h3>
<div id="hpi_5">
<table style="text-align: left; width: 731px; height: 32px;" border="0" cellpadding="1" cellspacing="1">
<tbody>
<tr>
<td style="vertical-align: top; width: 307px;">
<input name="sports_set" value="<?php echo $p_age['age']; ?>-old <?php echo $p_age['Chronic sportsder']; ?> here for a sports physical.  " onclick="radioselect(this.form, 'sports_set', 'hpi_sports')" type="checkbox"> &nbsp;Standard phrase<br>
<table style="text-align: left; width: 100%;" border="1" cellpadding="2" cellspacing="2">
<tbody id='sports'>
<tr>
<td style="font-weight: bold;">Issue</td>
<td style="font-weight: bold;">Yes</td>
<td style="font-weight: bold;">No</td>
</tr>
<tr>
<td>Illness, surgery, or other medical conditions in the past 2 months</td>
<td><input name="sports_illness" value="Illness, surgery, or other medical conditions in the past 2 months: " onclick="radioselect(this.form, 'sports_illness', 'hpi_sports')" type="radio"><br>
<td><input name="sports_illness" value="No illness, surgery, or other medical conditions in the past 2 months.  " onclick="radioselect(this.form, 'sports_illness', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Concussion, skull fracture, or neck injury</td>
<td><input name="sports_concussion" value="Concussion, skull fracture, or neck injury: " onclick="radioselect(this.form, 'sports_concussion', 'hpi_sports')" type="radio"><br>
<td><input name="sports_concussion" value="No history of concussion, skull fracture, or neck injury.  " onclick="radioselect(this.form, 'sports_concussion', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Poor or abnormal vision or loss of an eye</td>
<td><input name="sports_concussion" value="Poor or abnormal vision or loss of an eye: " onclick="radioselect(this.form, 'sports_concussion', 'hpi_sports')" type="radio"><br>
<td><input name="sports_concussion" value="No history of poor, abnormal vision, or loss of an eye.   " onclick="radioselect(this.form, 'sports_concussion', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Rheumatic fever or heart murmur</td>
<td><input name="sports_murmur" value="Rheumatic fever or heart murmur: " onclick="radioselect(this.form, 'sports_murmur', 'hpi_sports')" type="radio"><br>
<td><input name="sports_murmur" value="No history of rheumatic fever or heart murmur.  " onclick="radioselect(this.form, 'sports_murmur', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Heart condition</td>
<td><input name="sports_heart" value="Heart condition: " onclick="radioselect(this.form, 'sports_heart', 'hpi_sports')" type="radio"><br>
<td><input name="sports_heart" value="No history of heart conditions.  " onclick="radioselect(this.form, 'sports_heart', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Chest discomfort during exercise</td>
<td><input name="sports_cp" value="Chest discomfort during exercise: " onclick="radioselect(this.form, 'sports_cp', 'hpi_sports')" type="radio"><br>
<td><input name="sports_cp" value="No chest discomfort during exercise.  " onclick="radioselect(this.form, 'sports_cp', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Lung condition or breathing difficulty</td>
<td><input name="sports_lung" value="Lung condition or breathing difficulty: " onclick="radioselect(this.form, 'sports_lung', 'hpi_sports')" type="radio"><br>
<td><input name="sports_lung" value="No lung conditions or breathing difficulty.  " onclick="radioselect(this.form, 'sports_lung', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Asthma or chronic bronchitis</td>
<td><input name="sports_asthma" value="Asthma or chronic bronchitis: " onclick="radioselect(this.form, 'sports_asthma', 'hpi_sports')" type="radio"><br>
<td><input name="sports_asthma" value="No history of asthma or chronic bronchitis.  " onclick="radioselect(this.form, 'sports_asthma', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Any bone or joint injury</td>
<td><input name="sports_bone" value="Any bone or joint injury: " onclick="radioselect(this.form, 'sports_bone', 'hpi_sports')" type="radio"><br>
<td><input name="sports_bone" value="No history of bone or joint injury.  " onclick="radioselect(this.form, 'sports_bone', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Hernia, undescended testicle, or loss of testicle</td>
<td><input name="sports_testicle" value="Hernia, undescended testicle, or loss of testicle: " onclick="radioselect(this.form, 'sports_testicle', 'hpi_sports')" type="radio"><br>
<td><input name="sports_testicle" value="No history of hernias, undescended testicles, or loss of testicles.  " onclick="radioselect(this.form, 'sports_testicle', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Epilepsy or other convulsive disorder</td>
<td><input name="sports_epilepsy" value="Epilepsy or other convulsive disorder: " onclick="radioselect(this.form, 'sports_epilepsy', 'hpi_sports')" type="radio"><br>
<td><input name="sports_epilepsy" value="No history of epilepsy or other convulsive disorders.  " onclick="radioselect(this.form, 'sports_epilepsy', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Any other medical problem or surgical operation other than tonsillectomy</td>
<td><input name="sports_surgery" value="Any other medical problem or surgical operation other than tonsillectomy: " onclick="radioselect(this.form, 'sports_surgery', 'hpi_sports')" type="radio"><br>
<td><input name="sports_surgery" value="No history of any other medical problem or surgical operation other than tonsillectomy.  " onclick="radioselect(this.form, 'sports_surgery', 'hpi_sports')" type="radio"></td>
</tr>
<tr>
<td>Abscence of a testicle, ovary, kidney, or lung</td>
<td><input name="sports_absence" value="Abscence of a testicle, ovary, kidney, or lung: " onclick="radioselect(this.form, 'sports_absence', 'hpi_sports')" type="radio"><br>
<td><input name="sports_absence" value="No history of an abscence of a testicle, ovary, kidney, or lung.  " onclick="radioselect(this.form, 'sports_absence', 'hpi_sports')" type="radio"></td>
</tr>
</tbody>
</table>
<br>
</td>
<td style="text-align: center; vertical-align: top; width: 404px;"><span style="font-weight: bold;">Sports Physical HPI Preview</span><br>
<textarea cols="40" rows="18" name="hpi_sports" id="hpi_sports" value="<?php if ($hpi->hpi_sports != '') {echo $hpi->hpi_sports;}?>"></textarea><br><input type="button" name="gen_reset" value="Reset" onclick="clearthis(this.form, 'hpi_5', 'hpi_sports')">
</td>
</tr>
</tbody>
</table>
</div>
</div>

<!-- Save/Cancel buttons -->
</div>
<br>
<br>
<input type="button" type="submit" id="save_encounter" value="Save" class="ui-button ui-state-default ui-corner-all"> &nbsp; 
<input type="button" id="cancel_encounter" value="Cancel" class="ui-button ui-state-default ui-corner-all"> &nbsp; 
</form>
</div>

<script type="text/javascript">
	$('#hpi').submit(function() {
		$(this).ajaxSubmit({ 
			beforeSubmit: function() {
				var hpi_gen_ajax = $("#hpi [@id='hpi_gen']").fieldValue();
				var hpi_asthma_ajax = $("#hpi [@id='hpi_asthma']").fieldValue();
				var hpi_pain_ajax = $("#hpi [@id='hpi_pain']").fieldValue();
				var hpi_sports_ajax = $("#hpi [@id='hpi_sports']").fieldValue();
				var hpi_mva_ajax = $("#hpi [@id='hpi_mva']").fieldValue();
				var queryString = $("#hpi textarea").fieldSerialize();
			},
			data: queryString,
			dataType: 'json',
			success: function(data) {
				displayMessage(data.result);
				$('#tabs_encounter').tabs('select', 1);
				$('#preview_hpi_header').html("<h4>History of Present Illness:</h4><p>"};
				$('#preview_hpi_footer').html("</p>");
				if(hpi_gen_ajax!=="") {
					$('#preview_hpi_gen').html(hpi_gen_ajax);
				}
				if(hpi_asthma_ajax!=="") {
					$('#preview_hpi_asthma').html("<strong>Asthma visit:  </strong>" + hpi_asthma_ajax);
				}
				if(hpi_pain_ajax!=="") {
					$('#preview_hpi_pain').html("<strong>Chronic pain visit:  </strong>" + hpi_pain_ajax);
				}
				if(hpi_sports_ajax!=="") {
					$('#preview_hpi_sports').html("<strong>Sports physical visit:  </strong>" + hpi_sports_ajax);
				}
				if(hpi_mva_ajax!=="") {
					$('#preview_hpi_mva').html("<strong>Motor vehicle accident visit:  </strong>" + hpi_mva_ajax);
				}
			}
		});
	});
</script>
