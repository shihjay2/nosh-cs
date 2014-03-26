# NOSH ChartingSystem Installation Instructions

## Preparation:
You'll need to have the following applications/packages installed on your system prior to installation
of NOSH ChartingSystem.  If you have a LAMP (Linux-Apache-mySQL-PHP) or MAMP (Mac-Apache-mySQL-PHP) server
already set up, you are golden!

##### 1. Apache web server (needs to be running)
##### 2. MySQL database.  Make sure you remember the root password.  This will be asked during the
NOSH ChartingSystem installation. (needs to be running)
##### 3. PHP 5.2 and higher
##### 4. The following PHP modules installed and enabled: 
mysql, imap, mcrypt, imagick, gd, cli, curl, soap, pear
##### 5. PERL
##### 6. Imagemagick
##### 7. PDF ToolKit (pdftk)
##### 8. cURL

## Installation
Installing NOSH ChartingSystem is very easy to install.  If you use Ubuntu, it is even easier as NOSH can be installed from its own 
Personal Package Archive (PPA).  With the [PPA method](https://github.com/shihjay2/nosh-cs/wiki/Installation#ubuntu-ppa), you do not 
have to worry about installing dependencies.  If you have access to a terminal shell for your server (any distro for Linux or 
Mac OS-X), you can install NOSH with the [Manual Method](https://github.com/shihjay2/nosh-cs/wiki/Installation#manual-method).  
The installation script automatically adds scheduled task commands (cron files) and web server configuration files to make NOSH 
work seamlessly the first time.  The script also determines if your system meets all the package dependencies before installation.
For detailed information, go to the [Wiki link](https://github.com/shihjay2/nosh-cs/wiki/Installation#ubuntu-ppa).

If this is the first time using NOSH, make sure you login to NOSH ChartingSystem as admin and configure your users and clinic 
parameters.  It's important to do this first before any other users use NOSH ChartingSystem; otherwise, 
some features such as scheduling will not work correctly!

## Updates
Like Laravel, NOSH now utilizes [Composer](http://getcomposer.org) to manage its PHP dependencies.  
Composer is automatically installed when you use the installation script (it is located in /usr/local/bin/composer).
Because the [core NOSH files](https://github.com/shihjay2/nosh-core/) are now served on GitHub, NOSH is self-updating daily
whenever a new commit (updated files) is uploaded.  There is no more user intervention anymore, and you get the latest and greatest 
NOSH version at your fingertips!

## Uninstall the package:
##### 1. Open a terminal window and go to the NOSH installation directory (the default in the script
is /usr/share/nosh).
##### 2. As a root user (sudo in Ubuntu/Debian), type "sh uninstall.sh" to run the uninstallation script.

# How the files are organized.

NOSH is built around the Laravel PHP Framework, which is a models/controllers/views (MCV) framework.
Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs).

## Routes
The routes.php file dictate where the URL command goes to.  Looking at the file, you'll notice that the controllers are
categorized by an access control list (ACL) based on the type of user priviledges a user has when he/she is logged in to NOSH.

## Controllers
As is standard with the Laravel framework, main guts of the system lie in the ../app/controllers directory.  Looking at the
routes.php file, you'll notice that the type of controllers are categorized between AJAX and non-AJAX functions (hence they 
are named with a prefix of Ajax).

## Views
The view files, PDF, and email template files are in the ../app/views directory.  The view files are essentially "modules" that
are added on depending on the needs of the view layout.
The corresponding javascript files (named the same as the view file, but with a .js extension) are in the ../public/js directory.

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

## Models
This is where php code pertaining to MySQL database functions reside.  The controllers frequently point to code in this 
directory.

## Assets
Images indicated in the view files reside in the ../public/images directory.
Imported files are usually downloaded via script in the ../import directory.

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

### Contributing To NOSH ChartingSystem

**All issues and pull requests should be filed on the [shihjay2/nosh-core](http://github.com/shihjay2/nosh-core) repository.**

### License

NOSH ChartingSystem is open-sourced software licensed under the [GNU Affero General Public License](http://www.gnu.org/licenses/)
