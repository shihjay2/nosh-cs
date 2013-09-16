#!/bin/bash
# Install script for Updox Sync in conjunction with nosh-cs

set -e
read -e -p "Enter the username for the Updox account: " -i "" UPDOX_USER
read -e -p "Enter the password for the Updox account: " -i "" UPDOX_PASS
read -e -p "Enter the MySQL user: " -i "root" DEFAULTUSERNAME
read -e -p "Enter the MySQL password: " -i "" DEFAULTPASSWORD
read -e -p "Enter the username of Ubuntu OS account that Updox Sync will autostart: " USER

# Constants and paths
LOG=/var/log/updox_installation_log
CONF=/home/$USER/connector.properties

log_only () {
	echo "$1"
	echo "`date`: $1" >> $LOG
}

get_settings () {
	echo `grep -i "^[[:space:]]*$1[[:space:]=]" $2 | cut -d \= -f 2 | cut -d \; -f 1 | sed "s/[ 	'\"]//gi"`
}

if [[ $EUID -ne 0 ]]; then
	echo "This script must be run as root" 1>&2
	exit 1
fi
JAVA=$(java -version 2>&1 | head -n 2 | tail -1 | awk '{print $1 $2 $3 $4}')
if [ "$JAVA" == "Java(TM)SERuntimeEnvironment" ]; then
	if [ ! -f $LOG ]; then
		touch $LOG
	fi
	if [ -d "/var/lib/mysql/nosh" ]; then
		NOSH_DIR=$(mysql -u$DEFAULTUSERNAME -p$DEFAULTPASSWORD "nosh" -sN -e "select documents_dir from practiceinfo where practice_id = '1'")
		log_only "Found NOSH ChartingSystem installation and documents directory is at $NOSH_DIR."
	else
		echo "Exiting...NOSH ChartingSystem is not installed  Install NOSH ChartingSystem first before proceeding."
		exit 1
	fi
	NOSH_DIR_CLEAN=`echo "$NOSH_DIR" | sed -e "s/\/*$//"`
	UPDOX_DOC_DIR=$NOSH_DIR_CLEAN/updox
	mkdir -p $UPDOX_DOC_DIR
	chown www-data.www-data $UPDOX_DOC_DIR
	log_only "Created Updox download directory."
	mkdir -p /home/$USER/updox
	cp /var/www/nosh/extensions/updox/updox.jnlp /home/$USER/updox/updox.jnlp
	log_only "Installed Updox Sync Java Web App."
	UPDOX_OWN=/home/$USER/updox/updoxowner
	touch $UPDOX_OWN
	echo "#!/bin/sh" >> $UPDOX_OWN
	echo "chgrp -R www-data $UPDOX_DOC_DIR" >> $UPDOX_OWN
	echo "chmod -R 775 $UPDOX_DOC_DIR" >> $UPDOX_OWN
	chmod +x $UPDOX_OWN
	UPDOX_CRON=/etc/cron.d/updox
	touch $UPDOX_CRON
	echo "*/1 *   * * *   $USER    $UPDOX_OWN" >> $UPDOX_CRON
	chown root.root $UPDOX_CRON
	chmod 644 $UPDOX_CRON
	touch $CONF
	echo "syncPatientData=$UPDOX_DOC_DIR" >> $CONF
	echo "syncPatientPath=" >> $CONF
	echo "apiPwd=$UPDOX_PASS" >> $CONF
	echo "runFreq=10" >> $CONF
	echo "syncAutoSaveData=$UPDOX_DOC_DIR" >> $CONF
	echo "apiRos=false" >> $CONF
	echo "syncPatientFile=[FN]_[LN]_[C]_[T]_[D]_[BD]" >> $CONF
	echo "apiUid=$UPDOX_USER" >> $CONF
	chown $USER.$USER $CONF
	chmod 644 $CONF
	log_only "Created configuration file for Updox Sync."
	/usr/bin/gpasswd -a $USER www-data
	log_only "Set $USER user to Apache group"
	UPDOX_START=/home/$USER/updox/updoxstart
	touch $UPDOX_START
	echo "#!/bin/sh" >> $UPDOX_START
	echo "/usr/bin/javaws /home/$USER/updox/updox.jnlp" >> $UPDOX_START
	echo "exit 0" >> $UPDOX_START
	chown $USER.$USER $UPDOX_START
	chmod 775 $UPDOX_START
	UPDOX_DESKTOP=/home/$USER/.config/autostart/updox.desktop
	touch $UPDOX_DESKTOP
	echo "[Desktop Entry]" >> $UPDOX_DESKTOP
	echo "Name=Updox Sync" >> $UPDOX_DESKTOP
	echo "Encoding=UTF-8" >> $UPDOX_DESKTOP
	echo "Type=Application" >> $UPDOX_DESKTOP
	echo "Exec=/home/mikey/updox/updox.jlnp" >> $UPDOX_DESKTOP
	chown $USER.$USER $UPDOX_DESKTOP
	chmod 644 $UPDOX_DESKTOP
	log_only "Updox Sync is now installed."
	exit 1
else
	echo "Exiting...Wrong version of the JAVA runtime environment.  You must install the Oracle JAVA JRE before proceeding."
	exit 1
fi
