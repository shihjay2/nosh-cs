# NOSH ChartingSystem Installation Instructions for Linux

## Preparation:
You'll need to have the following programs installed on your system prior to installation
of NOSH ChartingSystem

##### 1. Apache web server (needs to be running)
##### 2. MySQL database.  Make sure you remember the root password.  This will be asked during the
NOSH ChartingSystem installation. (needs to be running)
##### 3. PHP 5.2 and higher
##### 4. The following PHP modules installed and enabled: 
mysql, imap, mcrypt, imagick, gd, cli, curl, soap, pear
##### 5. PERL
##### 6. Imagemagick
##### 7. PDF ToolKit (pdftk)
```
## Install the package:

##### 1. Unzip the installation file in a directory of your choice.  Go to the "nosh-cs" directory.
##### 2. Open a terminal window and go to the "nosh-cs" directory.
##### 3. Type "sh install.sh" to run the installation script.
##### 4. Follow the instructions on the prompt.
##### 5. Go to your web browser and type, "http://localhost/nosh" to begin the second stage of
installation.
##### 6. Wait for installation to complete.
##### 7. Login to NOSH ChartingSystem as admin and configure your users and clinic parameters.
It's important to do this first before any other users use NOSH ChartingSystem; otherwise, 
some features such as scheduling will not work correctly!
```
## Uninstall the package:
##### 1. Open a terminal window and go to the "nosh-cs" directory where you ran the installation
script.
##### 2. Type "sh uninstall.sh" to run the uninstallation script.
```
# How the files are organized.

NOSH is built around the Codeigniter PHP Framework, which is a models/controllers/views (MCV) framework.
Please look at the Codeigniter website and their documentation to review how this framework operates (http://codeigniter.com).
Those familiar with it, will note that the main, configurable files are placed in the ../nosh/system/applications directory.
Inside this directory are the following subdirectories and their contents:

## controllers
	This directory containes files that are the main guts of the application.  There are several user types that are 
	subcategorized within this directory.  These user types are: provider, assistant, billing, admin, and patient.

## Within each user type, these are the common main files and their descriptions:
##### chartmenu.php - This is the main page when a chart is opened.  A large majority of functions appear in this file.
##### encounters.php - This is where functions pertaining to a patient encounter within a chart reside.
##### billing.php - This is where functions pertaining to patient billing reside.
##### messaging.php - This is where functions pertaining to messaging (intra-office messaging, fax, scanning, address book) 
##### reside.

## views
	This directory contains files that the user "sees" when information is passed onto the browser.  All the Javascript codes 
	are placed here.  In the first directory within views, these are the installation view files that require no authorization to 
	access.  There is a subdirectory "auth" that contains the header and footer of all pages.

	If you see the javascript, you will notice that jQuery is used heavily here.  There are numerous plugins for jQuery that are 
	referenced in the header file.  Below is a list of the major jQuery plugins that are used:
##### Javascript user interface: JQuery UI (dialog, tabs, accordion, autocomplete)
##### Calendar system: FullCalendar
##### Tables and grids: jqGrid
##### Comment tips: BeautyTips
##### Signature capture: Signature Pad
##### Growth charts: Highcharts
##### Form input masking: Masked Input
##### Date/time input: Time entry

	The header file points to all above javascript files placed in the /nosh/js directory.  The header file also points to all css 
	files that are placed in the ../nosh/css directory.

## model
	This is where php code pertaining to MySQL database functions reside.  The controllers frequently point to code in this 
	directory.

## config
	This is where configuration options for Codeigniter exist.  In general, all settings should remain unchanged unless any 
	new feature requires a system-wide configuration change.

	Images indicated in the view files reside in the ../nosh/images directory.
	Imported files are usually downloaded via script in the /nosh/import directory.

## Database schema
	Below are the list of database tables that are installed for NOSH.  Some table names are self explainatory, but those that are not
	will be explained here.
	addressbook
	alerts
	allergies
	assessment - Assessment of a patient encounter.
	audit - This is a log of all database commands (add, edit, delete) by users of NOSH.
	billing - List of all fields in a HCFA-1500 form for each patient encounter.
	billing-core - List of all charges and payments for a patient encounter.
	calendar - List of all visit types and their duration for the patient scheduler.
	ci_sessions - This table keeps track of all active user sessions at a given time.
	cpt - CPT codes
	cvx - CVX (vaccine database) codes
	demographics - List of all patients (active or inactive) in the system.
	documents - List of all PDF documents saved in the documents folder (default is /noshdocuments) on the server that pertain to a
		given patient.
	encounters - List of all patient encounters for a given patient.
	groups - List of user groups (provider, admin, assistant, billing, patient).
	hippa - List of all release of information requests for a given patient.
	hpi - History of Present Illness of a patient encounter.
	icd9 - ICD9 (and also ICD10, if updated) codes
	immunizations
	insurance - List of all insurance information for a given patient.
	issues - List of all medical issues (active or inactive) for a given patient.
	labs - List of all lab results for a given patient.
	meds - List of all FDA regulated medications.
	messaging - Intraoffice messaging.
	npi - NPI taxonomy codes.
	orders - This table lists all physician orders for a given patient.
	orderslists - This table lists all templates for physician orders.
	other_history - Past Medical History, Past Surgical History, Family History. Social History, Tobacco Use History, Alcohol Use 
		History, and Illicit Drug Use History
	pages - List of documents being sent by fax.
	pe - Physical Examination of a patient encounter.
	plan - Plan of a patient encounter.
	pos - Place of Service codes
	practiceinfo - Practice information
	procedure - Procedures done in a patient encounter.
	procedurelist - Procedure templates.
	providers - Provider information
	received - List of documents received by fax.
	recepients - List of recepients of faxes sent.
	repeat_schedule - List of repeated calendar events.
	ros - Review of System of a patient encounter.
	rx - List of all medications (active or inactive) for a given patient.
	rx_list - List of all medications prescribed by a provider.
	scans - List of all documents scanned into the system.
	schedule - Patient scheduling
	sendfax - List of all sent faxes.
	sup_list - List of all ordered supplements by physician.
	supplements_list - List of all supplements in NIH.
	template - List of physician templates for History of Present Illness, Review of Systems, Physical Examination (unused table for 
		now).
	tests - This is an unused table (for now).
	t_messages - List of all telephone messages for a given patient.
	users - List of all system users.
	vaccine_inventory
	vaccine_temp - Vaccine temperature log
	vitals - List of vital signs in a patient encounter.
