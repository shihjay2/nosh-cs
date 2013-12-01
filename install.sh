#!/bin/sh
# install script for nosh-cs

set -e
cp -a usr/ /usr/
read -e -p "Enter the directory where NOSH ChartingSystem patient files will be stored:" -i "/noshdocuments" NOSH_DIR

# Constants and paths
LOGDIR=/var/log/nosh
LOG=$LOGDIR/installation_log
WEB=/var/www
NOSH=$WEB/nosh
CONFIGDATABASEBACKUP=$NOSH/system/application/config/database_backup.php
CONFIGDATABASE=$NOSH/system/application/config/database.php
WEB_GROUP=www-data
WEB_USER=www-data
PHP=/etc/php5/apache2/php.ini
OLDNOSHREMINDER=/usr/bin/noshreminder.php
OLDNOSHFAX=/usr/bin/fax.php
NOSHBACKUP=/usr/bin/noshbackup
NOSHCRON=/etc/cron.d/nosh-cs
NOSHREMINDER=/usr/bin/noshreminder
NOSHFAX=/usr/bin/noshfax
INSTALL_VIEW=$NOSH/system/application/views/install.php

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
	echo `grep -i "^[[:space:]]*$1[[:space:]=]" $2 | cut -d \= -f 2 | cut -d \; -f 1 | sed "s/[ 	'\"]//gi"`
}

insert_settings () {
	sed -i 's%^[ 	]*'"$1"'[ 	=].*$%'"$1"' = '"$2"'%' "$3"
}

# Check prerequisites
type apache2 >/dev/null 2>&1 || { echo >&2 "Apache Web Server is required, but it's not installed.  Aborting."; exit 1; }
type mysql >/dev/null 2>&1 || { echo >&2 "MySQL is required, but it's not installed.  Aborting."; exit 1; }
type php >/dev/null 2>&1 || { echo >&2 "PHP is required, but it's not installed.  Aborting."; exit 1; }
type perl >/dev/null 2>&1 || { echo >&2 "Perl is required, but it's not installed.  Aborting."; exit 1; }
type pdftk >/dev/null 2>&1 || { echo >&2 "PDFTK is required, but it's not installed.  Aborting."; exit 1; }
type convert >/dev/null 2>&1 || { echo >&2 "ImageMagick is required, but it's not installed.  Aborting."; exit 1; }
type sshd >/dev/null 2>&1 || { echo >&2 "SSH Server is required, but it's not installed.  Aborting."; exit 1; }

# Secure NOSH ChartingSystem
chown -R $WEB_GROUP.$WEB_USER $NOSH
chmod -R 755 $NOSH
chown root.root $NOSHREMINDER
chmod 777 $NOSHREMINDER
chown root.root $NOSHFAX
chmod 777 $NOSHFAX
if [ -f $NOSHCRON ]; then
	rm -rf $NOSHCRON
fi
if [ ! -f $LOG ]; then
	touch $LOG
fi
touch $NOSHCRON
echo "*/10 *  * * *   root    /usr/bin/noshfax" >> $NOSHCRON
echo "*/1 *   * * *   root    /usr/bin/noshreminder" >> $NOSHCRON
echo "0 0     * * *   root    /usr/bin/noshbackup" >> $NOSHCRON
chown root.root $NOSHCRON
chmod 644 $NOSHCRON
/bin/egrep  -i "^ftpshared" /etc/group
if [ $? -eq 0 ]; then
	log_only "Group ftpshared already exists."
else
	groupadd ftpshared
	log_only "Group ftpshared does not exist.  Making group."
fi
if [ -d $FTPIMPORT ]; then
	log_only "The NOSH ChartingSystem SFTP directories already exist."
else
	mkdir -p $FTPIMPORT
	mkdir -p $FTPEXPORT
	chown -R root:ftpshared /srv/ftp/shared
	chmod 755 /srv/ftp/shared
	chmod -R 775 /srv/ftp/shared/import
	chmod -R 775 /srv/ftp/shared/export
	chmod g+s /srv/ftp/shared/import
	chmod g+s /srv/ftp/shared/export
	log_only "The NOSH ChartingSystem SFTP directories have been created."
	/usr/bin/gpasswd -a www-data ftpshared
	cp /etc/ssh/sshd_config /etc/ssh/sshd_config.bak
	log_only "Backup of SSH config file created."
	sed -i '/Subsystem/s/^/#/' /etc/ssh/sshd_config
	echo 'Subsystem sftp internal-sftp' >> /etc/ssh/sshd_config
	echo 'Match Group ftpshared' >> /etc/ssh/sshd_config
	echo 'ChrootDirectory /srv/ftp/shared' >> /etc/ssh/sshd_config
	echo 'X11Forwarding no' >> /etc/ssh/sshd_config
	echo 'AllowTCPForwarding no' >> /etc/ssh/sshd_config
	echo 'ForceCommand internal-sftp' >> /etc/ssh/sshd_config
	log_only "SSH config file updated."
fi
# Check to ensure the php configuration file exists
if [ -f $PHP ]; then
	# Collect php variables from php.ini
 	collect_php () {
		echo `grep -i "^[;[:space:]]*$1[[:space:]=]" $PHP | cut -d \= -f 2 | cut -d \; -f 1 | sed 's%[ 	M]%%gi'`
	}
	ARRAYS_TEXT="register_long_arrays"
	TAG=$(collect_php "$ARRAYS_TEXT")
	MAGIC_TEXT="magic_quotes_gpc"
	MAGIC=$(collect_php "$MAGIC_TEXT")
	FILESIZE_TEXT="upload_max_filesize"
	FILESIZE=$(collect_php "$FILESIZE_TEXT")
	POSTSIZE_TEXT="post_max_size"
	POSTSIZE=$(collect_php "$POSTSIZE_TEXT")
	
	# Backup the php.ini file before modification
	cp $PHP $PHP.BAK
	log_only "A backup of your php configuration has been created at $PHP.BAK."

	# Modify pertinent php.ini variables
	FLAG_ON=0
	process_php () {
		if [ "$3" -eq "1" ]; then
			# Make recommendations to php.ini
			if [ "$FLAG_ON" -eq "0" ]; then
				log_only "The following setting(s) have been modified in your php configuration file at $PHP :"
			fi      
			FLAG_ON=1
		else
			# Modify php.ini
			sed -i "s%^[; 	]*$1[ 	=].*$%$1 = $2%" $PHP
			log_only "Successfully set $1 = $2"  
		fi
	}
	for i in `seq 1 2`; do
		if [ "$TAG" != "Off" ]; then
			process_php "$ARRAYS_TEXT" "Off" $i
		fi
		if [ "$MAGIC" != "Off" ]; then
			process_php "$MAGIC_TEXT" "Off" $i
		fi
		if [ "$FILESIZE" != "100M" ]; then
			process_php "$FILESIZE_TEXT" "100M" $i
		fi
		if [ "$POSTSIZE" != "100M" ]; then
			process_php "$POSTSIZE_TEXT" "100M" $i
		fi
		if [ "$FLAG_ON" -eq "0" ]; then
			log_only "Your PHP configuration is set for using for NOSH ChartingSystem."
			break
		fi
	done
else
	# php configuration file cannot be found, so we just echo the instructions
	log_only "We recommend ensuring you have these settings in your php configuration file:"
	log_only "register_long_arrays = Off"
	log_only "magic_quotes_gpc = Off"
	log_only "upload_max_filesize = 100M"
	log_only "post_max_size = 100M"
fi
# Check if there was a previous installation of NOSH ChartingSystem
if [ -d $NOSH ]; then
	rm -rf $OLDNOSHFAX
	rm -rf $OLDNOSHREMINDER
	if [ -e "$CONFIGDATABASE" ]; then
		cp -fr $CONFIGDATABASE $CONFIGDATABASEBACKUP
		log_only "Backup of Codeigniter database configuration file"
	fi
	cp -a var/ /var/
fi
if [ -e $CONFIGDATABASEBACKUP ]; then
	DEFAULTUSERNAME=$(get_settings \$default_db_username $CONFIGDATABASEBACKUP)
	DEFAULTPASSWORD=$(get_settings \$default_db_password $CONFIGDATABASEBACKUP)
	insert_settings "\$default_db_username" "\'$DEFAULTUSERNAME\';" $CONFIGDATABASE
	insert_settings "\$default_db_password" "\'$DEFAULTPASSWORD\';" $CONFIGDATABASE
	log_only "Imported previous settings in the Codeigniter database configuartion file."
	NOSH_DIR=$(mysql -u$DEFAULTUSERNAME --password=$DEFAULTPASSWORD "nosh" -sN -e "select documents_dir from practiceinfo where practice_id = '1'")
	log_only "The NOSH ChartingSystem documents directory has been referenced from your previous installation."
	chown -R $WEB_GROUP.$WEB_USER $NOSH_DIR
	chmod -R 755 $NOSH_DIR
	if [ -d "$NOSH_DIR"scans ]; then
		chmod -R 777 "$NOSH_DIR"scans
	fi
	log_only "The NOSH ChartingSystem documents directory is secured."
	rm -rf $TMPDIR
	log_only "Temporary files have been removed"
	log_only "You can now use your newly updated NOSH ChartingSystem!"
else
	if [ "$NOSH_DIR_PRE" = "" ]; then
		log_only "The NOSH ChartingSystem documents directory will need to be created and secured manually."
	else
		NOSH_DIR=$NOSH_DIR_PRE
		if [ -d $NOSH_DIR ]; then
			log_only "The NOSH ChartingSystem documents directory already exists."
		else
			mkdir $NOSH_DIR
			log_only "The NOSH ChartingSystem documents directory has been created."
		fi
		chown -R $WEB_GROUP.$WEB_USER "$NOSH_DIR"
		chmod -R 755 $NOSH_DIR
		if [ -d "$NOSH_DIR"scans ]; then
			chmod -R 777 "$NOSH_DIR"scans
		fi
		log_only "The NOSH ChartingSystem documents directory is secured."
	fi
	# Set up SSL for Apache server
	if ! [ -L /etc/apache2/sites-enabled/default-ssl ]; then
		log_only "Setting up Apache to use SSL"
		ln -s /etc/apache2/sites-available/default-ssl /etc/apache2/sites-enabled/default-ssl
	fi
	a2enmod ssl
	# Installation completed
	log_only "You can now complete your new installation of NOSH ChartingSystem by browsing to:"
	log_only "https://localhost/nosh"
fi
log_only "Restarting Apache service"
invoke-rc.d apache2 restart >> $LOG 2>&1
log_only "Restarting SSH server service"
invoke-rc.d ssh restart >> $LOG 2>&1
exit 0
