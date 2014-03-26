#!/bin/sh
# uninstall script for nosh-cs

set -e

#constants and paths
LOGDIR=/var/log/nosh
LOG=$LOGDIR/installation_log
WEB=/var/www
NOSH=$WEB/nosh
LARAVEL=/var/laravel
CONFIGDATABASE=/var/laravel/.env.php
NOSHREMINDER=/usr/bin/noshreminder.php
NOSHREMINDER1=/usr/bin/noshreminder
NOSHFAX=/usr/bin/fax.php
NOSHFAX1=/usr/bin/noshfax
NOSHBACKUP=/usr/bin/noshbackup
NOSHCRON=/etc/cron.d/nosh-cs

log_only () {
	echo "$1"
	echo "`date`: $1" >> $LOG
}

unable_exit () {
	echo "$1"
	echo "`date`: $1" >> $LOG
	echo "EXITING.........."
	echo "`date`: EXITING.........." >> $LOG
	exit 1
}

get_settings () {
	echo `grep -i "^[[:space:]]*$1[[:space:]=>]" $2 | cut -d \> -f 2 | cut -d \, -f 1 | sed "s/[ 	'\"]//gi"`
}

if [ -d "/var/lib/mysql/nosh" ]; then
	# Collect database variables
	DEFAULTUSERNAME=$(get_settings \'mysql_username\' $CONFIGDATABASE)
	DEFAULTPASSWORD=$(get_settings \'mysql_password\' $CONFIGDATABASE)
	NOSH_DIR=$(mysql -u$DEFAULTUSERNAME -p$DEFAULTPASSWORD "nosh" -sN -e "select documents_dir from practiceinfo where practice_id = '1'")
	# Remove mysql database
	mysqladmin -f -u$DEFAULTUSERNAME -p$DEFAULTPASSWORD drop "nosh" >> $LOG 2>&1
	log_only "Removed NOSH ChartingSystem MySQL database"
fi
# Remove web directory
rm -rf $NOSH
rm -rf $LARAVEL
log_only "Removed NOSH ChartingSystem web directory and Larvel framework files."
# Remove rest of supporting files
rm -rf $NOSHREMINDER
rm -rf $NOSHREMINDER1
rm -rf $NOSHCRON
rm -rf $NOSHFAX
rm -rf $NOSHFAX1
rm -rf $NOSHBACKUP
log_only "Removed NOSH ChartingSystem supplemental files."
log_only "You will need to remove the directory $NOSH_DIR on your own based on your clinic patient confidentiality practices."
log_only "You may want to save this directory since all database backups are also stored here for future re-installation."

exit 0
