<form id="orders_form">
	<div id="orders_labs_tip" style="display:none"></div>
	<div id="orders_rad_tip" style="display:none"></div>
	<div id="orders_cp_tip" style="display:none"></div>
	<div id="orders_ref_tip" style="display:none"></div>
	<div id="orders_rx_tip" style="display:none"></div>
	<div id="orders_sup_tip" style="display:none"></div>
	<div id="orders_imm_tip" style="display:none"></div>
	<button type="button" id="save_orders">Save</button>
	<button type="button" id="print_orders">Print Orders Summary</button>
	<button type="button" id="cancel_orders">Cancel</button>
	<input type="hidden" name="orders_plan_old" id="orders_plan_old"/>
	<input type="hidden" name="orders_duration_old" id="orders_duration_old"/>
	<input type="hidden" name="orders_followup_old" id="orders_followup_old"/>
	<hr />
	<table>
		<tr>
			<td valign="top">
				<table>
					<tr>
						<td valign="top">
							Recommendations:<br>
							<input type="button" id="orders_plan_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"><br>
							<input type="button" id="orders_plan_instructions" value="Instructions" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"><br>
							<button type="button" id="encounter_letter" style="font-size: 0.8em">Letter</button>
						</td>
						<td valign="top">
							<textarea style="width:400px" rows="6" name="plan" id="orders_plan" class="text ui-widget-content ui-corner-all"></textarea><br><br>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Follow Up:<br><input type="button" id="orders_followup_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all"><br>
						</td>
						<td valign="top">
							<textarea style="width:400px" rows="2" name="followup" id="orders_followup" class="text ui-widget-content ui-corner-all"></textarea><br><br>
						</td>
					</tr>
				</table><br>
				<table>
					<tr>
						<td valign="top">
							Face-to-face time (in minutes), if greater than 50% of the visit:<br>
							<input type="text" style="width:100px" name="duration" id="orders_duration" class="text ui-widget-content ui-corner-all">
							<input type="button" id="orders_duration_reset" value="Clear" style="font-size: 0.8em" class="ui-button ui-state-default ui-corner-all">
						</td>
					</tr>
				</table>
			<td valign="top">
				<table>
					<tr>
						<td><div id="button_orders_labs_status"><?php echo $labs_status;?></div></td>
						<td><input type="button" id="button_orders_labs" value="Lab" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_rad_status"><?php echo $rad_status;?></div></td>
						<td><input type="button" id="button_orders_rad" value="Imaging" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_cp_status"><?php echo $cp_status;?></div></td>
						<td><input type="button" id="button_orders_cp" value="Cardiopulmonary" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_ref_status"><?php echo $ref_status;?></div></td>
						<td><input type="button" id="button_orders_ref" value="Referrals" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_rx_status"><?php echo $rx_status;?></div></td>
						<td><input type="button" id="button_orders_rx" value="RX" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_sup_status"><?php echo $sup_status;?></div></td>
						<td><input type="button" id="button_orders_supplements" value="Supplements" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
					<tr>
						<td><div id="button_orders_imm_status"><?php echo $imm_status;?></div></td>
						<td><input type="button" id="button_orders_imm" value="Immunizations" class="ui-button ui-state-default ui-corner-all"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<div id="orders_plan_instructions_dialog" title="Patient Instructions" style="font-size: 0.9em">
	<input type="hidden" id="instructions_chosen" />
	<div id="instructions_items">
		<h3><a href="#">The Sports Medicine Patient Advisor - Online</a></h3>
		<div id="instructions_items1">
			<b>Head and Neck</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>002.pdf" target="_blank">Concussion</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>004.pdf" target="_blank">Corneal Abrasions</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>006.pdf" target="_blank">Neck Spasms</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>008.pdf" target="_blank">Neck Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>011.pdf" target="_blank">Nose Injury</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>012.pdf" target="_blank">Otitis Externa/Swimmer's Ear</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>014.pdf" target="_blank">Stinger/Burner/Brachial Plexus</a><br><br>
			<b>Shoulder</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>018.pdf" target="_blank">Biceps Tendonitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>020.pdf" target="_blank">Frozen Shoulder/Adhesive Capsulitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>022.pdf" target="_blank">Labral Tear</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>024.pdf" target="_blank">Rhomboid Strain or Spasm</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>026.pdf" target="_blank">Rotator Cuff Injury</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>029.pdf" target="_blank">Shoulder Bursitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>031.pdf" target="_blank">Shoulder Dislocation</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>035.pdf" target="_blank">Shoulder Separation</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>039.pdf" target="_blank">Shoulder Subluxation</a><br><br>
			<b>Elbow and Arm</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>044.pdf" target="_blank">Elbow/Olecranon Bursitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>045.pdf" target="_blank">Forearm Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>046.pdf" target="_blank">Lateral Epicondylitis/Tennis Elbow</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>049.pdf" target="_blank">Medial Apophysitis/Little Leaguer's Elbow</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>052.pdf" target="_blank">Medial Epicondylitis/Golfer's Elbow</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>055.pdf" target="_blank">Osteochondritis Dissecans/Bone Chips of Elbow</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>056.pdf" target="_blank">Radial Head Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>058.pdf" target="_blank">Triceps Tendonitis</a><br><br>
			<b>Wrist and Hand</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>062.pdf" target="_blank">Carpal Tunnel Syndrome</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>065.pdf" target="_blank">DeQuervain's Tenosynovitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>067.pdf" target="_blank">Finger Dislocation</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>069.pdf" target="_blank">Finger Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>071.pdf" target="_blank">Finger Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>073.pdf" target="_blank">Ganglion Cyst</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>074.pdf" target="_blank">Ganglion Cyst Removal/Ganglionectomy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>076.pdf" target="_blank">Mallet Finger/Baseball Finger</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>078.pdf" target="_blank">Fifth Metacarpal Fracture/Boxer's Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>080.pdf" target="_blank">Navicular/Scaphoid Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>083.pdf" target="_blank">Thumb Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>085.pdf" target="_blank">Triangular Fibrocartilage Complex Injuries</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>088.pdf" target="_blank">Trigger Finger</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>089.pdf" target="_blank">Ulnar Collateral Ligament Sprain/Skier's Thumb</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>091.pdf" target="_blank">Ulnar Neuropathy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>093.pdf" target="_blank">Wrist Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>095.pdf" target="_blank">Wrist Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>098.pdf" target="_blank">Wrist Tendonitis</a><br><br>
			<b>Chest and Abdomen</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>102.pdf" target="_blank">Abdominal Muscle Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>105.pdf" target="_blank">Broken Collarbone/Fractured Clavicle</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>108.pdf" target="_blank">Groin/Inguinal Hernia</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>110.pdf" target="_blank">Rib Injury</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>112.pdf" target="_blank">Sports Hernia</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>114.pdf" target="_blank">Sternoclavicular Joint Separation</a><br><br>
			<b>Back</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>118.pdf" target="_blank">Herniated Disc</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>122.pdf" target="_blank">Low Back Pain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>126.pdf" target="_blank">Sacroiliac pain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>129.pdf" target="_blank">Scoliosis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>131.pdf" target="_blank">Spondylolysis and Spondylolisthesis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>133.pdf" target="_blank">Tailbone</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>134.pdf" target="_blank">Upper Back Pain</a><br><br>
			<b>Hip, Thigh, and Pelvis</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>138.pdf" target="_blank">Femur Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>140.pdf" target="_blank">Gluteal Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>143.pdf" target="_blank">Groin Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>146.pdf" target="_blank">Hamstring Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>149.pdf" target="_blank">Hip Flexor Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>151.pdf" target="_blank">Hip Pointer</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>152.pdf" target="_blank">Osteitis Pubis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>154.pdf" target="_blank">Pelvic Avulsion Fractures</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>156.pdf" target="_blank">Piriformis Syndrome</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>158.pdf" target="_blank">Quadriceps Contusion and Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>161.pdf" target="_blank">Snapping Hip Syndrome</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>163.pdf" target="_blank">Trochanteric Bursitis</a><br><br>
			<b>Knee</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>168.pdf" target="_blank">Anterior Cruciate Ligament Injury</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>172.pdf" target="_blank">Anterior Cruciate Ligament Reconstruction</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>174.pdf" target="_blank">Arthroscopic Meniscectomy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>176.pdf" target="_blank">Baker's Cyst</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>177.pdf" target="_blank">Iliotibial Band Syndrome</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>180.pdf" target="_blank">Knee Arthroscopy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>182.pdf" target="_blank">Lateral Collateral Ligament Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>185.pdf" target="_blank">Medial Collateral Ligament Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>188.pdf" target="_blank">Meniscal Tear</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>192.pdf" target="_blank">Oscgood-Schlatter Disease</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>195.pdf" target="_blank">Osteochondritis Dissecans of Knee</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>197.pdf" target="_blank">Patellar Subluxation</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>200.pdf" target="_blank">Patellar Tendonitis/Jumper's Knee</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>204.pdf" target="_blank">Patellofemoral Pain Syndrome/Runner's Knee</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>208.pdf" target="_blank">Pes Anserine Bursitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>211.pdf" target="_blank">Posterior Cruciate Ligament Injury</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>214.pdf" target="_blank">Prepatellar Bursitis</a><br><br>
			<b>Leg</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>218.pdf" target="_blank">Calf Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>221.pdf" target="_blank">Peroneal Tendon Strain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>224.pdf" target="_blank">Posterior Tibial Tendonitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>227.pdf" target="_blank">Shin Pain/Splints</a><br><br>
			<b>Ankle</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>232.pdf" target="_blank">Achilles Tendon Injury</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>235.pdf" target="_blank">Ankle Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>239.pdf" target="_blank">Broken Ankle</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>243.pdf" target="_blank">Chronic Ankle Laxity</a><br><br>
			<b>Foot</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>248.pdf" target="_blank">Arch Pain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>251.pdf" target="_blank">Bunion/Hallux Valgus</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>253.pdf" target="_blank">Bunion Removal</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>254.pdf" target="_blank">Calcaneal Apophysitis/Sever's Disease</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>256.pdf" target="_blank">Fifth Metatarsal Fracture</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>259.pdf" target="_blank">Foot Sprain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>262.pdf" target="_blank">Ingrown Toenail</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>263.pdf" target="_blank">Metatarsalgia</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>265.pdf" target="_blank">Morton's Neuroma</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>267.pdf" target="_blank">Over-Pronation</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>268.pdf" target="_blank">Plantar Fasciitis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>272.pdf" target="_blank">Plantar Warts</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>273.pdf" target="_blank">Turf Toe</a><br><br>
			<b>Nutrition</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>276.pdf" target="_blank">Caffeine and Athletic Performance</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>277.pdf" target="_blank">Calcium</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>279.pdf" target="_blank">Creatine</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>280.pdf" target="_blank">Fluids and Hydration</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>282.pdf" target="_blank">The Healthy Diet</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>285.pdf" target="_blank">Iron</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>287.pdf" target="_blank">Precompetition Meal</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>289.pdf" target="_blank">Eating Healthy Snacks</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>291.pdf" target="_blank">Weight Gain</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>293.pdf" target="_blank">Weight Loss</a><br><br>
			<b>General Medical Conditions</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>296.pdf" target="_blank">Altitude Sickness</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>298.pdf" target="_blank">Anabolic Steroids</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>299.pdf" target="_blank">Anorexia Nervosa</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>301.pdf" target="_blank">Athlete's Foot</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>302.pdf" target="_blank">Athletic Amenorrhea</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>303.pdf" target="_blank">Blisters</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>304.pdf" target="_blank">Bulimia Nervosa</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>306.pdf" target="_blank">How to Choose and Use a Cane</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>308.pdf" target="_blank">Cast Care</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>309.pdf" target="_blank">Compartment Syndrome</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>310.pdf" target="_blank">Cortisone Injection</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>311.pdf" target="_blank">Crutches: How to Use</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>312.pdf" target="_blank">Elastic Bandage: How to Use</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>313.pdf" target="_blank">Exercise-Induced Asthma</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>314.pdf" target="_blank">Frostbite</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>316.pdf" target="_blank">Heat Illness & Exercising in the Heat</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>318.pdf" target="_blank">Heat Therapy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>319.pdf" target="_blank">Herpes Gladiatorum</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>320.pdf" target="_blank">Hypothermia</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>321.pdf" target="_blank">Impetigo</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>322.pdf" target="_blank">Ice Therapy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>323.pdf" target="_blank">Jet Lag</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>324.pdf" target="_blank">Magnetic Resonance Imaging (MRI)</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>325.pdf" target="_blank">Mouth Guards</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>326.pdf" target="_blank">Muscle Spasms</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>327.pdf" target="_blank">Muscle Strains</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>328.pdf" target="_blank">Osteoarthritis</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>330.pdf" target="_blank">Physical Therapy</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>332.pdf" target="_blank">Proper Sitting, Standing, and Lifting at Work</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>335.pdf" target="_blank">Ringworm</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>336.pdf" target="_blank">Running Shoes: Finding the Right Fit</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>338.pdf" target="_blank">How to Use a Sling</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>339.pdf" target="_blank">Sprains</a><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>340.pdf" target="_blank">Stress Fractures</a><br><br>
			<b>Strength Conditioning</b><br>
			<a href="<?php echo base_url().'patiented/SMA/';?>343.pdf" target="_blank">Strength Conditioning</a><br><br>
		</div>
		<h3><a href="#">Emergency Medical Instructions - Ohio State University</a></h3>
		<div id="instructions_items2">
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>allergy.pdf" target="_blank">Allergic Reaction: Emergency Department</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>asthma.pdf" target="_blank">Asthma (Emergency Department)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>back-str.pdf" target="_blank">Back Pain and Back Strain: Care After Treatment</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>barthil.pdf" target="_blank">Bartholin Cyst or Abscess</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>bites.pdf" target="_blank">Bites and Stings: Bees, Wasps, and Yellow Jackets</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>gastro.pdf" target="_blank">Care After Treatment for Gastroenteritis</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>corneal.pdf" target="_blank">Care After Treatment: Corneal Abrasion</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>nose.pdf" target="_blank">Care After Treatment: Nose Bleed</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>sprained.pdf" target="_blank">Care After Treatment: Sprained Ankle</a>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>uri-adul.pdf" target="_blank">Care After Treatment: Upper Respiratory Infection (URI) Adult</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>uri-kid.pdf" target="_blank">Care After Treatment: Upper Respiratory Infection (URI) Children</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>uti.pdf" target="_blank">Care After Treatment: Urinary Tract Infection (UTI)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>cast-cr.pdf" target="_blank">Cast Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>chicken.pdf" target="_blank">Chicken Pox</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>childfev.pdf" target="_blank">Childhood Fever</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>complet.pdf" target="_blank">Complete Miscarriage</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>concuss.pdf" target="_blank">Concussion or Mild TBI</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>crutwal.pdf" target="_blank">Crutch Walking</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>dir-alcdrugserv.pdf" target="_blank">Directory of Alcohol &amp; Drug Addiction Services</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>emer-bmt.pdf" target="_blank">Emergency Care for Blood and Marrow Patients</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>dental-care.pdf" target="_blank">Emergency Dental Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>eye.pdf" target="_blank">Eye Drops</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>femalsex.pdf" target="_blank">Female Sexual Assault</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>gal-stn.pdf" target="_blank">Gallstones</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>vag-inf.pdf" target="_blank">Guide to Vaginal Infections</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>herpes.pdf" target="_blank">Herpes Simplex Virus (HSV)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>hi-bp.pdf" target="_blank">High Blood Pressure</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>minor-sur.pdf" target="_blank">Home Care After Minor Surgery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>fol-fem.pdf" target="_blank">Home Care for Your Foley Catheter (Female)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>fol-male.pdf" target="_blank">Home Care for Your Foley Catheter (Male)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>heimlich-flutter.pdf" target="_blank">Home Care of the Heimlich Flutter Valve</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>intra-py.pdf" target="_blank">Intravenous Pyelogram (IVP): Emergency Department</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>kid-ston.pdf" target="_blank">Kidney Stones</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>malesex.pdf" target="_blank">Male Sexual Assault</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>minorbur.pdf" target="_blank">Minor Burn Injuries</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>mono.pdf" target="_blank">Mononucleosis</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>otitis.pdf" target="_blank">Otitis Media: Middle Ear Infection</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>pneumonia.pdf" target="_blank">Pneumonia: Out-Patient Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>pois-ivy.pdf" target="_blank">Poison Ivy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>ptsd.pdf" target="_blank">Post Traumatic Stress Disorder (PTSD)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>pri-care-serv.pdf" target="_blank">Primary Care Services</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>sexual.pdf" target="_blank">Sexually Transmitted Infections</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>shingles.pdf" target="_blank">Shingles - Herpes Zoster</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>sore.pdf" target="_blank">Sore Throat - Pharyngitis - Care After Treatment</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>tetanus.pdf" target="_blank">Tetanus and Diphtheria Vaccine: What You Need to Know</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>threat.pdf" target="_blank">Threatened Miscarriage</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>undrstnd.pdf" target="_blank">Understanding HIV / AIDS: Are Your at Risk?</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>using-spacer-inhaler.pdf" target="_blank">Using a Spacer with Your Inhaler</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>raped.pdf" target="_blank">When Someone You Know Has Been Raped</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/emergency/';?>wound-care.pdf" target="_blank">Wound Care: Emergency Department</a><br>
		</div>
		<h3><a href="#">Women's Health Instructions - Ohio State University</a></h3>
		<div id="instructions_items3">
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>antep-let.pdf" target="_blank">Antepartum Unit</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>baby-play-learn-nicu.pdf" target="_blank">Baby Learning in the NICU</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>FamilyCentered.pdf" target="_blank">Family Centered Maternity Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>oupt-obstrec.pdf" target="_blank">Obstetric Outpatient Record</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pcos.pdf" target="_blank">Polycystic Ovary Syndrome</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>preconception-care.pdf" target="_blank">Preconception Care: Things To Do Before You Become Pregnant</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>dear-par.pdf" target="_blank">Welcome to the Neonatal Intensive Care Unit (NICU)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>barthil.pdf" target="_blank">Bartholin Cyst or Abscess</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>breast-self-exam.pdf" target="_blank">Breast Self-Exam - Micromedex</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>clomid.pdf" target="_blank">Clomid</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>common-herbal.pdf" target="_blank">Common Herbal Remedies for Women's Health</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>cryosur.pdf" target="_blank">Cryosurgery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>dilation-curettage.pdf" target="_blank">Dilatation and Curettage (D&amp;C)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>endomebi.pdf" target="_blank">Endometrial Biopsy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>exercises-hysterectomy.pdf" target="_blank">Exercises  After Your Hysterectomy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>Essure-Birth-Control.pdf" target="_blank">Female Sterilization: Essure</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>genital-warts.pdf" target="_blank">Genital Warts</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>3-hour-glucose-test.pdf" target="_blank">Glucose Tolerance Test (GTT): OSU OB Clinic</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>vag-inf.pdf" target="_blank">Guide to Vaginal Infections</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>female.pdf" target="_blank">Having a Female Pelvic Exam</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>homcasur.pdf" target="_blank">Home Care After Your Gynecological Surgery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hpv.pdf" target="_blank">Human Papillomavirus (HPV)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hyst-reg.pdf" target="_blank">Hysterectomy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bre-lmp.pdf" target="_blank">Information about Changes in the Breast</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pelv-exo.pdf" target="_blank">Information About Pelvic Exenteration</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>simp-vul.pdf" target="_blank">Information About Your Simple or Partial Vulvectomy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>inter-ra.pdf" target="_blank">Internal Radiation for Gynecological Cancers</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>kegel.pdf" target="_blank">Kegel or Pelvic Floor Exercises for Women</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>laser-tr.pdf" target="_blank">Laser Treatment for Gynecology</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>leep.pdf" target="_blank">Loop Electrosurgical Excision Procedure - LEEP</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>manage-menopause.pdf" target="_blank">Managing Menopause</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>perineal.pdf" target="_blank">Perineal Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>mammogr.pdf" target="_blank">Questions and Answers about Having a Mammogram</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hyst-rad.pdf" target="_blank">Radical Hysterectomy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>rad-vul.pdf" target="_blank">Radical Vulvectomy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>menstrual-cycle.pdf" target="_blank">The Menstrual Cycle</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>urinary-incontinence.pdf" target="_blank">Urinary Incontinence in Women</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>uterinefibroid.pdf" target="_blank">Uterine Fibroid Embolization</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>vaginal-dil.pdf" target="_blank">Vaginal Dilator</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>vag-dry.pdf" target="_blank">Vaginal Dryness</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>women-epilepsy.pdf" target="_blank">Women with Epilepsy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>voiding-record.pdf" target="_blank">Your Bladder Diary</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>papsmear.pdf" target="_blank">Your Pap Smear Test</a><br>
		</div>
		<h3><a href="#">Postpartum Instructions - Ohio State University</a></h3>
		<div id="instructions_items4">
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>careself.pdf" target="_blank">Caring for Yourself After Delivery: Home Care Instruction Guide</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>cesarean.pdf" target="_blank">Cesarean Birth</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>discommo.pdf" target="_blank">Common Discomforts After Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>DischargeEdChecklist.pdf" target="_blank">Discharge Education Checklist: Your Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>EdinburghScale.pdf" target="_blank">Edinburgh Postnatal Depression Scale</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>exerc-cs.pdf" target="_blank">Exercises after a Cesarean Section</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>st-incont.pdf" target="_blank">Helpful Tips for Stress Incontinence</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>breastca.pdf" target="_blank">How to Care for Your Breasts After Having a Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>postpartum-paincontrol.pdf" target="_blank">Pain Control after Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>perinea.pdf" target="_blank">Perineal Care after Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>sexafter.pdf" target="_blank">Sexual Activity after Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>skin-to-skin.pdf" target="_blank">Skin to Skin Contact with Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>post-ck.pdf" target="_blank">The Postpartum Check-Up</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>when2cal.pdf" target="_blank">When to Call Your Health Care Provider after Delivery</a> <br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>baby-blues.pdf" target="_blank">Your Emotions after Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>cesrecov.pdf" target="_blank">Your Recovery After a Cesarean Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>vaginal.pdf" target="_blank">Your Recovery After Vaginal Delivery</a><br>
		</div>
		<h3><a href="#">Pregnancy Instructions - Ohio State University</a></h3>
		<div id="instructions_items5">
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>alcohol.pdf" target="_blank">Alcohol and Other Drug Use During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>alphafet.pdf" target="_blank">Alpha-Fetoprotein (AFP) Blood Test</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>amniocen.pdf" target="_blank">Amniocentesis: Commonly Asked Questions and Answers</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>asthma-pregnancy.pdf" target="_blank">Asthma in Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>junk.pdf" target="_blank">Avoiding Junk Food during Pregnancy: Nutritious Treats</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>preg-rest.pdf" target="_blank">Bedrest Exercises for Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>depo-provera.pdf" target="_blank">Birth Control: Depo-Provera (Depot-Medroxyprogesterone Acetate - DMPA)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>breath-tech.pdf" target="_blank">Breathing Techniques for Labor</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>cerclage.pdf" target="_blank">Cervical Cerclage</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>CesareanBirthInstr.pdf" target="_blank">Cesarean Birth Instructions</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>chronhbp.pdf" target="_blank">Chronic High Blood Pressure in Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>clomid.pdf" target="_blank">Clomid</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>common-discomforts.pdf" target="_blank">Common Discomforts of Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>cordocen.pdf" target="_blank">Cordocentesis</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>danger-signs.pdf" target="_blank">Danger Signs of Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>deterdue.pdf" target="_blank">Determining Your Due Date</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetge.pdf" target="_blank">Diabetes During Pregnancy - Gestational Diabetes</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>exercpre.pdf" target="_blank">Exercises for Pregnancy and Childbirth</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>FamilyCentered.pdf" target="_blank">Family Centered Maternity Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>fibrotest.pdf" target="_blank">Fetal Fibronectin Test</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>fetal.pdf" target="_blank">Fetal Movement Count</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>group-b-strep.pdf" target="_blank">Group B Strep in Pregnancy and After Birth</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>labor-sg.pdf" target="_blank">Guide for Labor Support Person</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>H1N1Pregnancy.pdf" target="_blank">H1N1 Flu and Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hea-pre.pdf" target="_blank">Healthy Eating During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>labor.pdf" target="_blank">How Do I Know When I'm in Labor?</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>Hydroxyprogesterone.pdf" target="_blank">Hydroxyprogesterone (17 P or 17-alpha-hydroxyprogesterone caproate)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hypereme.pdf" target="_blank">Hyperemesis Gravidarum (HG)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>InductionLaborInstr.pdf" target="_blank">Induction of Labor Instructions</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>laborgu.pdf" target="_blank">Labor Guide</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>minor-pro.pdf" target="_blank">Minor Problems During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>morning.pdf" target="_blank">Morning Sickness: Nausea and Vomiting of Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>non-stress-test.pdf" target="_blank">Non-Stress Test in Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>1HourGlucoseScreen.pdf" target="_blank">One Hour Glucose Screen</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>otc-medicines-pregnancy.pdf" target="_blank">Over the Counter Medicines During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>packing.pdf" target="_blank">Packing for the Hospital to Have Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pain-relief-labor-preg.pdf" target="_blank">Pain Relief During Labor and Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>placent.pdf" target="_blank">Placenta Previa</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-clam.pdf" target="_blank">Preeclampsia</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-act.pdf" target="_blank">Pregnancy Activity Levels</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-rup.pdf" target="_blank">Premature Rupture of the Membranes (PROM)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-exa.pdf" target="_blank">Prenatal Care Examination</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>prenatal-info-card.pdf" target="_blank">Prenatal Information Card</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-test.pdf" target="_blank">Prenatal Testing</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>prepare.pdf" target="_blank">Preparing for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>preterm.pdf" target="_blank">Preterm Labor</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>CMV-preg.pdf" target="_blank">Prevent Cytomegalovirus (CMV) During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ProtectingYouBabyH1N1.pdf" target="_blank">Protecting Yourself and Your Baby from H1N1 Flu</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>relax-tech-labor.pdf" target="_blank">Relaxation Techniques for Labor and Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>RhNegative.pdf" target="_blank">Rh Negative Blood and Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>rubella.pdf" target="_blank">Rubella and Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>sexdur.pdf" target="_blank">Sex During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ThyroidProblemsPreg.pdf" target="_blank">Thyroid Problems during Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>treat-preeclampsia.pdf" target="_blank">Treatment for Preeclampsia</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>treat-preterm-labor.pdf" target="_blank">Treatment for Preterm Labor</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>VBAC.pdf" target="_blank">Vaginal Birth after Cesarean Section (VBAC)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>welcom-labor-let.pdf" target="_blank">Welcome to Labor and Delivery</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>HCGLetter.pdf" target="_blank">Women's Health Clinic: HCG Letter</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/2.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 2nd Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/3.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 3rd Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/4.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 4th Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/5.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 5th Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/6.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 6th Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/7.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 7th Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/8.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 8th Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/9.pdf" target="_blank">Your Baby and Your Body During Pregnancy - 9th Month</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>months/1.pdf" target="_blank">Your Baby and Your Body During Pregnancy - Conception to Week 8</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/diabtcar.pdf" target="_blank">After Delivery for the Woman with Diabetes</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/blood-sugar-record.pdf" target="_blank">Blood Sugar Record for Pregnant Women with Diabetes</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/blood-sugar-record-insulin.pdf" target="_blank">Blood Sugar Record for Pregnant Women with Diabetes Taking Insulin</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/checklist-diab-preg.pdf" target="_blank">Checklist for Diabetes in Pregnancy: Your Responsibilities</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/diabetnp.pdf" target="_blank">Diabetes and Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/firsttri.pdf" target="_blank">Diabetes and Pregnancy: First Trimester (1 to 3 Months)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/secondtr.pdf" target="_blank">Diabetes and Pregnancy: Second Trimester (4 to 6 Months)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/thirdtri.pdf" target="_blank">Diabetes and Pregnancy: Third Trimester (7 to 9 Months)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetge.pdf" target="_blank">Diabetes During Pregnancy - Gestational Diabetes</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/dealing-sick-days-preg.pdf" target="_blank">Diabetes During Pregnancy: Dealing with Sick Days</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>draw-one-preg.pdf" target="_blank">Drawing Up One Insulin in Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/feelings-diab.pdf" target="_blank">Feelings About Being Pregnant and Having Diabetes</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/GlucoseTesting-preg.pdf" target="_blank">Glucose Testing During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/highbloodsugar-diab.pdf" target="_blank">High Blood Sugar in Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>insulin-injectable-preg.pdf" target="_blank">Injectable Insulin During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/insulin-sites-preg.pdf" target="_blank">Insulin Sites During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>mixing-preg.pdf" target="_blank">Mixing Two Insulins in Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/SickDayMealPlan-Preg.pdf" target="_blank">Sick Day Meal Plan for Diabetes During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>taking-insulin-preg.pdf" target="_blank">Taking Insulin During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/TestUrineKetones-Preg.pdf" target="_blank">Testing Urine for Ketones During Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diabetes/treatmen-diab.pdf" target="_blank">Treatment for Low Blood Sugar in Pregnancy (Hypoglycemia, Insulin Reaction)</a><br>
		</div>
		<h3><a href="#">Infant Instructions - Ohio State University</a></h3>
		<div id="instructions_items6">
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>H1N1Child.pdf" target="_blank">About H1N1 Flu and Your Child</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>paincntrl-newborn.pdf" target="_blank">About Pain and Pain Control for the Healthy Newborn</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>aboutpain-premature-infant.pdf" target="_blank">About Pain and Pain Control for the Premature Infant</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>b-b-101-agenda.pdf" target="_blank">Baby Basics 101 - Agenda</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>baby-ability.pdf" target="_blank">Baby's Ability to Organize or Self-Regulate</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-inf-blood-trans.pdf" target="_blank">Blood Transfusions for Preterm Babies</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>brach-pl.pdf" target="_blank">Brachial Plexus Injury in Babies</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>calm-baby-sugarwater.pdf" target="_blank">Calming Baby with Sugar Water</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>drug-withdrawal.pdf" target="_blank">Caring for a Baby with Drug Withdrawal</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>carebaby.pdf" target="_blank">Caring for Your Baby: Home Care Instruction Guide</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>caring-late-preterm.pdf" target="_blank">Caring for Your Late Preterm Infant</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>choose.pdf" target="_blank">Choosing a Health Care Provider for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>circu.pdf" target="_blank">Circumcision Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>clavicle-fracture.pdf" target="_blank">Clavicle Fracture in a Newborn</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ComfortCareDrug.pdf" target="_blank">Comfort Care for a Baby with Drug Withdrawal</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>mass-inf.pdf" target="_blank">Comfort Touch and Infant Massage: A Guide for Parents of a Hospitalized Term Infant</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>cop-cry.pdf" target="_blank">Coping with Your Baby's Crying</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>develop-behav.pdf" target="_blank">Development and Behavior of the Premature Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>DischargeEdChecklist-baby.pdf" target="_blank">Discharge Education Checklist: Your Baby's Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>essentials-mults.pdf" target="_blank">Essentials For Multiples</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>fact.pdf" target="_blank">Facts for Fathers</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>nicu-visit.pdf" target="_blank">Family Visitation in the Neonatal Intensive Care Unit</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>general.pdf" target="_blank">General Care for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>mass-pre.pdf" target="_blank">Giving Comfort Touch and Massage to a Preterm Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>mass-gui.pdf" target="_blank">Guidelines for Infant Massage</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>sharing.pdf" target="_blank">Helpful Hints for Sharing Among Children</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>help-hnt.pdf" target="_blank">Helpful Hints to Parents with Babies in the NICU</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>homsaf.pdf" target="_blank">Home Safety Tips for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>burp.pdf" target="_blank">How to Burp Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bathe.pdf" target="_blank">How to Give Your Newborn Baby a Bath</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>diaper.pdf" target="_blank">How to Prevent Diaper Rash</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>suction.pdf" target="_blank">How to Suction Your Baby's Nose with a Bulb Syringe</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>immun.pdf" target="_blank">Immunizations from Birth to Six Years</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>infant-cpr.pdf" target="_blank">Infant CPR</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>infant-dev.pdf" target="_blank">Infant Development: The First Year of Life</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ivh.pdf" target="_blank">Intraventricular Hemorrhage (IVH)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>tummy-time.pdf" target="_blank">It's Tummy Time</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>jaundice.pdf" target="_blank">Jaundice in Newborn Babies</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>kangaroo.pdf" target="_blank">Kangaroo Care</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>keepingb.pdf" target="_blank">Keeping Your Baby Safe at Home</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>keep-bsh.pdf" target="_blank">Keeping Your Baby Safe: Hospital Safety</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>knowingb.pdf" target="_blank">Knowing When Your Baby is ILL</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>MRSAbaby.pdf" target="_blank">MRSA and Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ResourcesChildren.pdf" target="_blank">New Baby Resources for Children</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ResourcesParents.pdf" target="_blank">New Baby Resources for Parents</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>newborn.pdf" target="_blank">Newborn Characteristics - How Your Baby will Look and Behave After Birth</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>NewbornScreenForm.pdf" target="_blank">Newborn Screening Information</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pda.pdf" target="_blank">Patent Ductus Arteriosus (PDA)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>perivent.pdf" target="_blank">Periventricular Leukomalacia (PVL)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>poison.pdf" target="_blank">Poison Prevention</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>prem-in-behav.pdf" target="_blank">Premature Infant Behavior</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ProtectingYouBabyH1N1.pdf" target="_blank">Protecting Yourself and Your Baby from H1N1 Flu</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>rds.pdf" target="_blank">Respiratory Distress Syndrome and Surfactant Therapy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>retinopa.pdf" target="_blank">Retinopathy from Premature Birth (R.O.P.)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>safpet.pdf" target="_blank">Safety Tips with Pets</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>safe-car.pdf" target="_blank">Safety with Car Seats and Booster Seats</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>smoking.pdf" target="_blank">Smoking and Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>social.pdf" target="_blank">Social Security Numbers for Newborns</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>suck.pdf" target="_blank">Suck and Swallow Activities for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>SIDS.pdf" target="_blank">Sudden Infant Death Syndrome (SIDS)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ThyroidProbBaby.pdf" target="_blank">Thyroid Problems in Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>TipsSoothing.pdf" target="_blank">Tips for Soothing Crying Babies</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>tips-more.pdf" target="_blank">Tips on Caring for More than One Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>jaundice-treating.pdf" target="_blank">Treating Jaundice in Newborn Babies</a>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>com-motor.pdf" target="_blank">Ways Babies Communicate: Motor System Signs of Stress</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>com-physical.pdf" target="_blank">Ways Babies Communicate: Physical Signs of Stress</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>com-state.pdf" target="_blank">Ways Babies Communicate: State System Signs of Stress</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>whatis-devcare.pdf" target="_blank">What is Developmental Care?</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hep-bvac.pdf" target="_blank">Why Does My Baby Need a Hepatitis B Vaccine?</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>BabyCareTeam.pdf" target="_blank">Your Baby's Care Team in the NICU</a> - <span class="docDate">New 9/3/2010</span><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>baby-self-reg.pdf" target="_blank">Your Baby's Self-Regulation Skills</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>your-babys-sleep.pdf" target="_blank">Your Baby's Sleep in the NICU</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>success.pdf" target="_blank">A Prescription for Success: A Guide for Women with Diabetes Planning Pregnancy</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bedside-diary-1.pdf" target="_blank">Baby's Bedside Diary - Day 1</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bedside-diary-2.pdf" target="_blank">Baby's Bedside Diary - Day 2</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bedside-diary-3.pdf" target="_blank">Baby's Bedside Diary - Day 3</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bedside-diary-4.pdf" target="_blank">Baby's Bedside Diary - Day 4</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bottle-diagram.pdf" target="_blank">Bottle Feeding More Than One Baby - Diagram</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bottlefe.pdf" target="_blank">Bottle Feeding Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>breast-diagram.pdf" target="_blank">Breastfeeding More Than One Baby - Diagram</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>first-48.pdf" target="_blank">Breastfeeding: The First 48 Hours</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>DailyBFRecordFirstWk.pdf" target="_blank">Daily Breastfeeding Record for the First Week</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>DailyPumpingRecord.pdf" target="_blank">Daily Pumping Record for First Ten Days</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>DonorHumanMilk.pdf" target="_blank">Donor Human Milk for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>FeedBreastMilkPrematureBaby.pdf" target="_blank">Feeding Breast Milk to Your Premature Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>feeding-premature.pdf" target="_blank">Feeding Your Premature Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>getting.pdf" target="_blank">Getting Started Breastfeeding Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>hea-bre.pdf" target="_blank">Healthy Eating While Breastfeeding</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>bottlepr.pdf" target="_blank">How to Bottle Feed Your Premature Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>steriliz.pdf" target="_blank">How to Sterilize Baby Bottles and Nipples</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>isbreast.pdf" target="_blank">Is Breastfeeding for Me?</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pre-engo.pdf" target="_blank">Preventing and Treating Engorgement</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>sorenipples-tx.pdf" target="_blank">Prevention and Treatment of Sore Nipples</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pump.pdf" target="_blank">Pumping and Storage of Breastmilk for the Healthy Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>pump-store.pdf" target="_blank">Pumping, Storing and Transporting Breast Milk for Infants in the NICU</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>brmilk2i.pdf" target="_blank">Tips to Help Increase Your Breastmilk Supply</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>nipple-shield.pdf" target="_blank">Using a Nipple Shield</a><br>
		</div>
		<h3><a href="#">Pediatric Instructions - Ohio State University</a></h3>
		<div id="instructions_items7">
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>H1N1Child.pdf" target="_blank">About H1N1 Flu and Your Child</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>behavior-modification.pdf" target="_blank">Behavior Modification</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>being-assertive.pdf" target="_blank">Being Assertive</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>catscan.pdf" target="_blank">CAT Scan of the Brain (for Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>child-cpr.pdf" target="_blank">Child CPR</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>childfev.pdf" target="_blank">Childhood Fever</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>dst.pdf" target="_blank">Dexamethasone Suppression Test (DST)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>funeral.pdf" target="_blank">Funeral Arrangements for Your Baby</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>healthy.pdf" target="_blank">Healthy Eating for Children</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>child-de.pdf" target="_blank">Helping Children Understand Death</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>high-fi.pdf" target="_blank">High Fiber Diet: Children</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>howinfan.pdf" target="_blank">How to Give Your Baby Medicine by Mouth</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>temperat.pdf" target="_blank">How to Take a Baby's Axillary Temperature</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>KeepSafeLead.pdf" target="_blank">Keep Your Family Safe from Lead</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>blood.pdf" target="_blank">My Blood Test (Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>ekg.pdf" target="_blank">My EKG (Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>sleepdep.pdf" target="_blank">My SDEEG - Sleep Deprived Electroencephalography (Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>urine.pdf" target="_blank">My Urine Test (Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>vital.pdf" target="_blank">My Vital Signs (Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>resour-child.pdf" target="_blank">Resources for Children and Families</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>safe.pdf" target="_blank">Safe Touch</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>sib-rivalry.pdf" target="_blank">Sibling Rivalry</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>toptest.pdf" target="_blank">T.O.P. Test</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>tourette.pdf" target="_blank">Touretter's Syndrome (Children)</a><br>
			<a href="<?php echo base_url().'patiented/PDFDocs/general/';?>mri.pdf" target="_blank">Your MRI (Children)</a><br>
		</div>
	</div>
</div>
<script type="text/javascript">
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/get_orders');?>",
		dataType: "json",
		success: function(data){
			$("#orders_plan").val(data.plan);
			$("#orders_duration").val(data.duration);
			$("#orders_followup").val(data.followup);
			$("#orders_plan_old").val(data.plan);
			$("#orders_duration_old").val(data.duration);
			$("#orders_followup_old").val(data.followup);
		}
	});
	$("#save_orders").button({
		icons: {
			primary: "ui-icon-disk"
		},
	});
	$("#print_orders").button({
		icons: {
			primary: "ui-icon-print"
		},
	});
	$("#cancel_orders").button({
		icons: {
			primary: "ui-icon-close"
		},
	});
	$("#save_orders").click(function(){
		var str = $("#orders_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/orders_save');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					var a = $("#orders_plan").val();
					var b = $("#orders_duration").val();
					var c = $("#orders_followup").val();
					$("#orders_plan_old").val(a);
					$("#orders_duration_old").val(b);
					$("#orders_followup_old").val(c);
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#print_orders").click(function(){
		var str = $("#orders_form").serialize();
		if(str){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('provider/encounters/orders_save');?>",
				data: str,
				success: function(data){
					$.jGrowl(data);
					var a = $("#orders_plan").val();
					var b = $("#orders_duration").val();
					var c = $("#orders_followup").val();
					$("#orders_plan_old").val(a);
					$("#orders_duration_old").val(b);
					$("#orders_followup_old").val(c);
					window.open("<?php echo site_url('provider/encounters/print_orders');?>");
				}
			});
		} else {
			$.jGrowl("Please complete the form");
		}
	});
	$("#cancel_orders").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('provider/encounters/get_orders');?>",
			dataType: "json",
			success: function(data){
				$("#orders_plan").val(data.plan);
				$("#orders_duration").val(data.duration);
				$("#orders_followup").val(data.followup);
				$("#orders_plan_old").val(data.plan);
				$("#orders_duration_old").val(data.duration);
				$("#orders_followup_old").val(data.followup);
			}
		});
	});
	$("#orders_plan_instructions_dialog").dialog({ 
		bgiframe: true, 
		autoOpen: false, 
		height: 500, 
		width: 800, 
		modal: true,
		draggable: false,
		resizable: false,
		open: function() {
			$("#instructions_items").accordion({active: false, fillSpace: true});
		},
		close: function(event, ui) {
			var b = $("#instructions_chosen").val();
			if (b != '') {
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
				var intro = 'Patient Instructions Given: ';
				$("#orders_plan").val(old1+intro+b);
			}
			$("#instructions_chosen").val('');
		}
	});
	$("#instructions_items1 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#instructions_items2 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#instructions_items3 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#instructions_items4 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#instructions_items5 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#instructions_items6 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#instructions_items7 > a").click(function(){
		var b = $(this).text();
		var old = $("#instructions_chosen").val();
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
		$("#instructions_chosen").val(old1+b);
	});
	$("#orders_plan").focus();
	$('#orders_plan_reset').button();
	$('#orders_plan_reset').click(function(){
		$("#orders_plan").val('');
	});
	$('#orders_plan_instructions').button();
	$('#orders_plan_instructions').click(function(){
		$("#orders_plan_instructions_dialog").dialog('open');
	});
	$("#encounter_letter").button();
	$("#encounter_letter").click(function() {
		$("#letter_dialog").dialog('open');
		$("#letter_eid").val('1');
	});
	$('#orders_duration_reset').button();
	$('#orders_duration_reset').click(function(){
		$("#orders_duration").val('');
	});
	$("#orders_followup_reset").button();
	$('#orders_followup_reset').click(function(){
		$("#orders_followup").val('');
	});
	$("#button_orders_labs").button();
	$("#button_orders_labs").click(function() {
		$("#edit_message_lab_form").show('fast');
		$("#save_lab_helper_label").html('Close Helper');
		$("#messages_lab_t_messages_id").val('');
		$("#messages_lab_origin").val('encounter');
		$("#messages_lab_header").show('fast');
		jQuery("#messages_lab_list").trigger("reloadGrid");
		$("#messages_lab_dialog").dialog('open');
	});
	$('#button_orders_labs_status').bt({
		contentSelector: "$('#orders_labs_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/labs');?>",
		success: function(data){
			$('#orders_labs_tip').html(data);
		}
	});
	$("#button_orders_rad").button();
	$("#button_orders_rad").click(function() {
		$("#edit_message_rad_form").show('fast');
		$("#save_rad_helper_label").html('Close Helper');
		$("#messages_rad_t_messages_id").val('');
		$("#messages_rad_origin").val('encounter');
		$("#messages_rad_header").show('fast');
		jQuery("#messages_rad_list").trigger("reloadGrid");
		$("#messages_rad_dialog").dialog('open');
	});
	$('#button_orders_rad_status').bt({
		contentSelector: "$('#orders_rad_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/rad');?>",
		success: function(data){
			$('#orders_rad_tip').html(data);
		}
	});
	$("#button_orders_cp").button();
	$("#button_orders_cp").click(function() {
		$("#edit_message_cp_form").show('fast');
		$("#save_cp_helper_label").html('Close Helper');
		$("#messages_cp_t_messages_id").val('');
		$("#messages_cp_origin").val('encounter');
		$("#messages_cp_header").show('fast');
		jQuery("#messages_cp_list").trigger("reloadGrid");
		$("#messages_cp_dialog").dialog('open');
	});
	$('#button_orders_cp_status').bt({
		contentSelector: "$('#orders_cp_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/cp');?>",
		success: function(data){
			$('#orders_cp_tip').html(data);
		}
	});
	$("#button_orders_ref").button();
	$("#button_orders_ref").click(function() {
		$("#edit_message_ref_form").show('fast');
		$("#save_ref_helper_label").html('Close Helper');
		$("#messages_ref_t_messages_id").val('');
		$("#messages_ref_origin").val('encounter');
		$("#messages_ref_header").show('fast');
		jQuery("#messages_ref_list").trigger("reloadGrid");
		$("#messages_ref_dialog").dialog('open');
	});
	$('#button_orders_ref_status').bt({
		contentSelector: "$('#orders_ref_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/ref');?>",
		success: function(data){
			$('#orders_ref_tip').html(data);
		}
	});
	$("#button_orders_rx").button();
	$("#button_orders_rx").click(function() {
		$("#messages_rx_dialog").dialog('open');
		$("#orders_rx_header").show('fast');
		$("#messages_rx_header").hide('fast');
	});
	$('#button_orders_rx_status').bt({
		contentSelector: "$('#orders_rx_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/rx');?>",
		success: function(data){
			$('#orders_rx_tip').html(data);
		}
	});
	$("#button_orders_supplements").button();
	$("#button_orders_supplements").click(function() {
		$("#supplements_list_dialog").dialog('open');
		$("#orders_supplements_header").show('fast');
		$("#orders_supplements").focus();
	});
	$('#button_orders_sup_status').bt({
		contentSelector: "$('#orders_sup_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/sup');?>",
		success: function(data){
			$('#orders_sup_tip').html(data);
		}
	});
	$("#button_orders_imm").button();
	$("#button_orders_imm").click(function() {
		$("#immunizations_list_dialog").dialog('open');
		$("#orders_imm_header").show('fast');
		$('#edit_immunization_form').hide('fast');
		$('#imm_order').show('fast');
		$('#imm_menu').hide('fast');
	});
	$('#button_orders_imm_status').bt({
		contentSelector: "$('#orders_imm_tip')",
		width: 500
	});
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('provider/encounters/tip_orders/imm');?>",
		success: function(data){
			$('#orders_imm_tip').html(data);
		}
	});
</script>
