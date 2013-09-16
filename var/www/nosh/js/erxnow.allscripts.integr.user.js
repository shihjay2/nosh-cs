// ==UserScript==
// @name		eRXNow Allscripts Integration for NOSH EMR
// @namespace	http://noshemr.org/
// @description   
// @include		https://erxnow.allscripts.com/*
// @include		*/nosh/index.php/provider/chartmenu*
// @include		*/nosh/index.php/provider/encounters*
// @require		https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js
// ==/UserScript==

// +-----------------------------------------------------------------------------+
// Copyright (C) 2011 Michael Chen <shihjay2@gmail.com>
//
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
//
// A copy of the GNU General Public License is included along with this program:
// NOSH EMR
// For more information write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// Author:   Michael Chen <shihjay2@gmail.com>
//
// +------------------------------------------------------------------------------+
asInputID={
	lblPatientName: "ctl00_lblPatientName",
	lblGenderDOB: "ctl00_lblGenderDob",
	txtPatLNAME: "ctl00_ContentPlaceHolder1_PatientSearch_txtLastName",
	txtPatFNAME: "ctl00_ContentPlaceHolder1_PatientSearch_txtFirstName",
	txtPatDOB1: "ctl00_ContentPlaceHolder1_PatientSearch_rdiDOB_text",
	txtPatDOB: "ctl00_ContentPlaceHolder1_PatientSearch_rdiDOB",
	txtMedication: "ctl00_ContentPlaceHolder1_txtFreeForm",
	txtSig: "ctl00_ContentPlaceHolder1_txtFreeTextSig",
	txtDay: "ctl00_ContentPlaceHolder1_txtDaysSupply",
	txtQuantity: "ctl00_ContentPlaceHolder1_txtQuantity",
	txtRefill: "ctl00_ContentPlaceHolder1_txtRefill",
	selUnit: "ctl00_ContentPlaceHolder1_ddlUnit",
	radMedication: "#ctl00_ContentPlaceHolder1_rblSearch_2",
	radSig: "ctl00_ContentPlaceHolder1_rdbFreeTextSig",
	ckDAW: "ctl00_ContentPlaceHolder1_chkDAW",
	tblViewPatients: "ctl00_ContentPlaceHolder1_grdViewPatients_ctl00",
	tblViewMedication: "ctl00_ContentPlaceHolder1_grdViewMed_ctl00",
	btnSearch: "ctl00_ContentPlaceHolder1_PatientSearch_btnSearch",
	btnMedication: "ctl00_ContentPlaceHolder1_btnFreeForm",
	btnSig: "ctl00_ContentPlaceHolder1_btnSelectSig",
	btnSelectMed1: "ctl00_ContentPlaceHolder1_btnSaveAndPrescribe",
	btnAddMed: "ctl00_ContentPlaceHolder1_btnAddReview",
	btnSubmit: "ctl00_ContentPlaceHolder1_btnSubmit",
	btnEdit: "ctl00_lnkEditPatient",
	btnSelectMed: "ctl00_ContentPlaceHolder1_btnFavorite",
	btnAddPatient: "ctl00_ContentPlaceHolder1_PatientSearch_btnAddPatient",
	linkEdit: "ctl00_lnkEditPatient",
	linkLogout: "ctl00_lnkLogout"
};

asAddPatientControls={
	btnPHRSetup: "ctl00_ContentPlaceHolder1_btnPatientPHR",
	txtPatFNAME: "ctl00_ContentPlaceHolder1_txtFName",
	txtPatLNAME: "ctl00_ContentPlaceHolder1_txtLName",
	txtPatDOB: "ctl00_ContentPlaceHolder1_txtDOB_text",
	txtPatAddr1: "ctl00_ContentPlaceHolder1_txtAddress1",
	txtPatPhone: "ctl00_ContentPlaceHolder1_txtPhone",
	txtPatMobilePhone: "ctl00_ContentPlaceHolder1_txtMobilePhone",
	txtPatCity: "ctl00_ContentPlaceHolder1_txtCity",
	txtPatZIP: "ctl00_ContentPlaceHolder1_txtZip",
	txtPatMRN: "ctl00_ContentPlaceHolder1_txtMRN",
	selGender: "ctl00_ContentPlaceHolder1_DDLGender",
	selState: "ctl00_ContentPlaceHolder1_ddlState"
};

function resetVariables()
{
	// Patient Info
	GM_setValue("e_firstname","");
	GM_setValue("e_lastname","");
	GM_setValue("e_dob","");
	GM_setValue("e_sex","");
	GM_setValue("e_address","");
	GM_setValue("e_city","");
	GM_setValue("e_state","");
	GM_setValue("e_zip","");
	GM_setValue("e_phone","");
	GM_setValue("e_mobile","");
	GM_setValue("e_pid","");	
	
	// Prescription Info
	GM_setValue("e_medication",""); // The Med Name
	GM_setValue("e_dosage",""); // The Med Strength
	GM_setValue("e_sig",""); // The Med SIG
	
    GM_setValue("search","not found");
    GM_setValue("function","");
    GM_setValue("process","");
}

function chooseSelect(control,option)
{
	sel=document.getElementById(control);
  	for(idx=0;idx<sel.options.length;idx++) {
		opt=sel.options[idx];
		if(opt.value==option) {
			sel.selectedIndex=idx;
			opt.click();
		}
	}	
}

function setGMvalue(noshControl)
{
    var val=$("#"+noshControl).val();
    GM_setValue(noshControl,val); 
}

function getGMvalue(asControl,noshControl)
{
	var val=GM_getValue(noshControl);
	$("#"+asAddPatientControls[asControl]).val(val);
}

function getGMvalue1(asControl,noshControl)
{
	var val=GM_getValue(noshControl);
	chooseSelect(asAddPatientControls[asControl],val);
}

function getGMvalue2(asControl,noshControl)
{
	var val=GM_getValue(noshControl);
	setFocus(asAddPatientControls[asControl]);
	$("#"+asAddPatientControls[asControl]).val(val);
}

function setFocus(id)
{
	var element = document.getElementById(id);
	if (element != null) {
		element.focus();
	}  
}

function setClick(id)
{
	var element = document.getElementById(id);
	if (element != null) {
		element.click();
	}
}

function wait(check, callback)
{
	if (check()) {
		callback();
	} else {
		window.setTimeout(wait, 300, check, callback);
	}
}

function asPopulateAndSearchPatientInfo()
{
    $("#"+asInputID['txtPatLNAME']).val(GM_getValue("e_lastname"));
    $("#"+asInputID['txtPatFNAME']).val(GM_getValue("e_firstname"));
    GM_setValue("search","searching");
    setClick(asInputID['btnSearch']);  
}

function asCheckPatientInfo()
{
	var pn=$("#"+asInputID['lblPatientName']).text();
	var name=GM_getValue("e_lastname")+", "+GM_getValue("e_firstname");
	var foundPatient=pn.toLowerCase().indexOf(name.toLowerCase());
	if(foundPatient===0) {
		var dob=$("#"+asInputID['lblGenderDOB']).text();
		var find_dob = GM_getValue("e_dob");
		var foundDOB=dob.indexOf(find_dob);
		if(foundDOB>=0) {
			GM_setValue("search","found");
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function asExecute()
{
	if(GM_getValue("function").indexOf("update")==0) {
		var linkid = document.getElementById(asInputID['linkEdit']);
		var link = linkid.getAttribute('href');
		location.assign(link);
	}
	if(GM_getValue("function").indexOf("send")==0) {
		setClick(asInputID['btnSelectMed']);
	}
}

function asFindPatientInResults()
{
	var myHTML=$(this).html();
	var name=GM_getValue("e_lastname")+", "+GM_getValue("e_firstname");
	var foundPatient=myHTML.toLowerCase().indexOf(name.toLowerCase());
	var noPatient=myHTML.indexOf("No patients found");
	if(foundPatient>=0) {
		var dob=GM_getValue("e_dob");
		var foundDOB=myHTML.indexOf(dob,foundPatient);
		if(foundDOB>=0) {
			var rowID=$(this).find("input[id]").attr("id");
			setClick(rowID);
			wait(asCheckPatientInfo,asExecute);	
		} else {
			setClick(asInputID['btnAddPatient']);
		}
	}
	if (noPatient>=0) {
		setClick(asInputID['btnAddPatient']);
	}
}

function asSearchPatient()
{
	if(($("#txtUserName").length>0) || (GM_getValue("process")==='')) {
		return;
	}
	if(GM_getValue("process")==="done") {
		var linkid1 = document.getElementById(asInputID['linkLogout']);
		var link1 = linkid.getAttribute('href');
		GM_setValue("process","");
		location.assign(link1);
	}
	if((GM_getValue("search")==="searching") || (GM_getValue("search")==="results scanning")) {
		tblViewPatients=$("#"+asInputID['tblViewPatients']);
		if(tblViewPatients.length>0) {
			GM_setValue("search","results scanning");
			rows=tblViewPatients.find("tbody tr");
			rows.each(asFindPatientInResults);	
		}
	}
	if(GM_getValue("search")==="not found") {
		asPopulateAndSearchPatientInfo();
	}
}

function loadDemographicsFromNOSH()
{
    getGMvalue('txtPatFNAME',"e_firstname");
	getGMvalue('txtPatLNAME',"e_lastname");
	getGMvalue('txtPatZIP',"e_zip");
	getGMvalue('txtPatAddr1',"e_address");
	getGMvalue('txtPatCity',"e_city");
	getGMvalue('txtPatPhone',"e_phone");
	getGMvalue('txtPatMobilePhone',"e_mobile");
	getGMvalue1('selGender',"e_sex");
	getGMvalue1('selState',"e_state");
	getGMvalue2('txtPatDOB',"e_dob");
	getGMvalue('txtPatMRN',"e_pid");
}

function asAddPatientUpdate()
{
	if(GM_getValue("e_lastname")==='') {
		return;
	}
	GM_setValue("search","not found");
	GM_setValue("function","");
	loadDemographicsFromNOSH();
	setClick(asInputID['btnSelectMed1']);
}

function asCheckFreeForm()
{
	var sig = document.querySelector("#"+asInputID['txtSig']);
	if (sig) {
		return true;
	} else {
		return false;
	}
}

function asClickFreeFormButton()
{
	if(GM_getValue("e_lastname")==='') {
		return;
	}
	setClick(asInputID['btnMedication']);
}

function asPrescription() {
	$("#"+asInputID['txtSig']).val(GM_getValue("e_sig"));
	$("#"+asInputID['txtDay']).val(GM_getValue("e_days"));
	var quantity = GM_getValue("e_quantity");
	if ((quantity.search(/ml/i)<0) || (quantity.search(/units/i)<0)) {
		$("#"+asInputID['txtQuantity']).val(GM_getValue("e_quantity"));
		$("#"+asInputID['selUnit']).val('EA');
	} else {
		var quantity1 = quantity.split(" ");
		$("#"+asInputID['txtQuantity']).val(quantity1[0]);
		if (quantity.search(/ml/i)>=0) {
			$("#"+asInputID['selUnit']).val('ML');
		} 
		if (quantity.search(/units/i)>=0) {
			$("#"+asInputID['selUnit']).val('UN');
		}
	}
	$("#"+asInputID['txtRefill']).val(GM_getValue("e_refills"));
	var daw = GM_getValue("e_daw");
	if (daw == 'Yes') {
		$("#"+asInputID['ckDAW']).attr('checked',true);
	} else {
		$("#"+asInputID['ckDAW']).attr('checked',false);
	}
	resetVariables();
	GM_setValue("process","done");
	setClick(asInputID['btnAddMed']);
}

$(document).ready(function(){
	$("#eprescribe_send_medications").on('click',function(){
		resetVariables();
		setGMvalue("e_medication");
		setGMvalue("e_dosage");
		setGMvalue("e_sig");
		setGMvalue("e_quantity");
		setGMvalue("e_refills");
		setGMvalue("e_days");
		setGMvalue("e_daw");
		setGMvalue("e_pid");
		setGMvalue("e_lastname");
		setGMvalue("e_firstname");
		setGMvalue("e_dob");
		GM_setValue("function","send");
		GM_setValue("process","ready");
		alert("Values exported. Go to the Allscripts website.");
	});
	$("#eprescribe_update_demographics").on('click',function(){
		resetVariables();
		setGMvalue("e_firstname");
		setGMvalue("e_lastname");
		setGMvalue("e_sex");
		setGMvalue("e_dob");
		setGMvalue("e_address");
		setGMvalue("e_city");
		setGMvalue("e_state");
		setGMvalue("e_zip");
		setGMvalue("e_phone");
		setGMvalue("e_mobile");
		setGMvalue("e_pid");
		setGMvalue("e_medication");
		setGMvalue("e_dosage");
		setGMvalue("e_sig");
		setGMvalue("e_quantity");
		setGMvalue("e_refills");
		setGMvalue("e_days");
		setGMvalue("e_daw");
		GM_setValue("function","update");
		GM_setValue("process","ready");
		alert("Values exported. Go to the Allscripts website.");
	});
	var loc=window.location.href;
	var pages={
		Interstitial: "/InterstitialAd.aspx",
		addPatient: "/AddPatient.aspx",
		editPatient: "/AddPatient.aspx?Mode=Edit",
		Default: "/default.aspx",
		Login: "/Login.aspx",
		refill: "/docrefillmenu.aspx",
		Fullscript: "/FullScript.aspx",
		Freeform: "/FreeFormDrug.aspx",
		Dur: "/RxDURReviewMultiSelect.aspx"
	};
	if(loc.indexOf(pages['Interstitial'])>=0) {
		var adButton = document.getElementById("adControl_closeButton");
		if (adButton != null) {
			adButton.click();
			return;
		}
	}
	if((loc.indexOf(pages['Default'])>=0) || (loc.indexOf(pages['Login'])>=0)) {
		asSearchPatient();
	}
	if((loc.indexOf(pages['addPatient'])>=0) || (loc.indexOf(pages['editPatient'])>=0)) {
		asAddPatientUpdate();
	}
	if(loc.indexOf(pages['Fullscript'])>=0) {
		asClickFreeFormButton();
	}
	if(loc.indexOf(pages['Freeform'])>=0) {
		if(GM_getValue("e_lastname")==='') {
			return;
		}
		$("#"+asInputID['txtMedication']).val(GM_getValue("e_medication")+" "+GM_getValue("e_dosage"));
		setClick(asInputID['btnSig']);
		wait(asCheckFreeForm,asPrescription);
	}
	if(loc.indexOf(pages['Dur'])>=0) {
		if(GM_getValue("process")==='') {
			return;
		}
		setClick(asInputID['btnSubmit']);
	}
});
