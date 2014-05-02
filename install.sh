#!/bin/sh
# install script for nosh-cs

set -e

# Constants and paths
LOGDIR=/var/log/nosh
LOG=$LOGDIR/installation_log
WEB=/var/www
NOSH=$WEB/nosh
OLDNOSH=$WEB/oldnosh
CONFIGDATABASEBACKUP=$OLDNOSH/system/application/config/database_backup.php
CONFIGDATABASE=$OLDNOSH/system/application/config/database.php
OLDNOSHREMINDER=/usr/bin/noshreminder.php
OLDNOSHFAX=/usr/bin/fax.php
NOSHBACKUP=/usr/bin/noshbackup
NOSHREMINDER=/usr/bin/noshreminder
NOSHFAX=/usr/bin/noshfax
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
	echo `grep -i "^[[:space:]]*$1[[:space:]=]" $2 | cut -d \= -f 2 | cut -d \; -f 1 | sed "s/[ 	'\"]//gi"`
}

insert_settings () {
	sed -i 's%^[ 	]*'"$1"'[ 	=].*$%'"$1"' = '"$2"'%' "$3"
}

# Check if running as root user
if [[ $EUID -ne 0 ]]; then
	echo "This script must be run as root.  Aborting." 1>&2
	exit 1
fi

# Check if previous NOSH installation.  If so, get database parameters.  If not, ask questions.
if [ -d $NOSH ]; then
	mv $NOSH $OLDNOSH
	log_only "Previous version of NOSH detected.  Backup of previous NOSH files to /var/www/oldnosh."
	rm -rf $OLDNOSHFAX
	rm -rf $OLDNOSHREMINDER
	rm -rf $NOSHFAX
	rm -rf $NOSHREMINDER
	rm -rf $NOSHBACKUP
	log_only "Removed old scripts for NOSH."
	if [ -e "$CONFIGDATABASE" ]; then
		cp -fr $CONFIGDATABASE $CONFIGDATABASEBACKUP
		log_only "Backup of Codeigniter database configuration file created."
	fi
fi
if [ -e $CONFIGDATABASE ]; then
	MYSQL_USERNAME=$(get_settings \$default_db_username $CONFIGDATABASE)
	MYSQL_PASSWORD=$(get_settings \$default_db_password $CONFIGDATABASE)
	MYSQL_DATABASE=nosh
	NOSH_DIR_PRE=$(mysql -u$MYSQL_USERNAME --password=$MYSQL_PASSWORD "nosh" -sN -e "select documents_dir from practiceinfo where practice_id = '1'")
	NOSH_DIR=${NOSH_DIR_PRE%?}
else
	read -e -p "Enter the directory where NOSH ChartingSystem patient files will be stored: " -i "/noshdocuments" NOSH_DIR
	read -e -p "Enter the name of the MySQL database that NOSH ChartingSystem will use: " -i "nosh" MYSQL_DATABASE
	read -e -p "Enter your MySQL username: " -i "" MYSQL_USERNAME
	read -e -p "Enter your MySQL password: " -i "" MYSQL_PASSWORD
fi

if [ "$NOSH_DIR" = "" ]; then
	echo "The NOSH ChartingSystem documents directory cannot be blank.  Aborting." 1>&2
	exit 1
else
	NEWNOSH=$NOSH_DIR/nosh-cs
	NEWNOSHTEST=$NEWNOSH/artisan
	TEMPCONFIGDATABASE=$NOSH_DIR/nosh-cs/.codeigniter.php
	NEWCONFIGDATABASE=$NOSH_DIR/nosh-cs/.env.php
	NOSHDIRFILE=$NEWNOSH/.noshdir
fi

# Check os and distro
if [[ "$OSTYPE" == "linux-gnu" ]]; then
	if [ -f /etc/debian_version ]; then
		# Ubuntu or Debian
		WEB_GROUP=www-data
		WEB_GROUP=www-data
		if [ -d /etc/apache2/conf-enabled ]; then
			WEB_CONF=/etc/apache2/conf-enabled
		else
			WEB_CONF=/etc/apache2/conf.d
		fi
		APACHE="/etc/init.d/apache2 restart"
		SSH="/etc/init.d/ssh stop"
		SSH1="/etc/init.d/ssh start"
	elif [ -f /etc/redhat-release ]; then
		# CentOS or RHEL
		WEB_GROUP=apache
		WEB_GROUP=apache
		WEB_CONF=/etc/httpd/conf.d
		APACHE="/etc/init.d/httpd restart"
		SSH="/etc/init.d/sshd stop"
		SSH1="/etc/init.d/sshd start"
	elif [ -f /etc/arch-release ]; then
		# ARCH
		WEB_GROUP=http
		WEB_GROUP=http
		WEB_CONF= /etc/httpd/conf/extra
		APACHE="systemctl restart httpd.service"
		SSH="systemctl stop sshd"
		SSH1="systemctl start sshd"
	elif [ -f /etc/gentoo-release ]; then
		# Gentoo
		WEB_GROUP=apache
		WEB_GROUP=apache
		WEB_CONF=/etc/apache2/modules.d
		APACHE=/etc/init.d/apache2
		SSH="/etc/init.d/sshd stop"
		SSH1="/etc/init.d/sshd start"
	elif [ -f /etc/fedora-release ]; then
		# Fedora
		WEB_GROUP=apache
		WEB_GROUP=apache
		WEB_CONF=/etc/httpd/conf.d
		APACHE="/etc/init.d/httpd restart"
		SSH="/etc/init.d/sshd stop"
		SSH1="/etc/init.d/sshd start"
	fi
elif [[ "$OSTYPE" == "darwin"* ]]; then
	# Mac
	WEB_GROUP=_www
	WEB_GROUP=_www
	WEB_CONF=/etc/httpd/conf.d
	APACHE="/usr/sbin/apachectl restart"
	SSH="launchctl unload com.openssh.sshd"
	SSH1="launchctl load com.openssh.sshd"
elif [[ "$OSTYPE" == "cygwin" ]]; then
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
elif [[ "$OSTYPE" == "win32" ]]; then
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
elif [[ "$OSTYPE" == "freebsd"* ]]; then
	WEB_GROUP=www
	WEB_GROUP=www
	WEB_CONF=/etc/httpd/conf.d
	if [ -e /usr/local/etc/rc.d/apache22.sh ]; then
		APACHE="/usr/local/etc/rc.d/apache22.sh restart"
	else
		APACHE="/usr/local/etc/rc.d/apache24.sh restart"
	fi
	SSH="/etc/rc.d/sshd stop"
	SSH1="/etc/rc.d/sshd start"
else
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
fi

# Check prerequisites
type apache2 >/dev/null 2>&1 || { echo >&2 "Apache Web Server is required, but it's not installed.  Aborting."; exit 1; }
type mysql >/dev/null 2>&1 || { echo >&2 "MySQL is required, but it's not installed.  Aborting."; exit 1; }
type php >/dev/null 2>&1 || { echo >&2 "PHP is required, but it's not installed.  Aborting."; exit 1; }
type perl >/dev/null 2>&1 || { echo >&2 "Perl is required, but it's not installed.  Aborting."; exit 1; }
type pdftk >/dev/null 2>&1 || { echo >&2 "PDFTK is required, but it's not installed.  Aborting."; exit 1; }
type convert >/dev/null 2>&1 || { echo >&2 "ImageMagick is required, but it's not installed.  Aborting."; exit 1; }
type sshd >/dev/null 2>&1 || { echo >&2 "SSH Server is required, but it's not installed.  Aborting."; exit 1; }
type curl >/dev/null 2>&1 || { echo >&2 "cURL is required, but it's not installed.  Aborting."; exit 1; }
log_only "All prerequisites for installation are met."

# Check apache version
APACHE_VER=$(apache2 -v | awk -F"[..]" 'NR<2{print $2}')

# Create cron scripts
if [ -f $NOSHCRON ]; then
	rm -rf $NOSHCRON
fi
if [ ! -f $LOG ]; then
	mkdir $LOGDIR
	touch $LOG
fi
touch $NOSHCRON
echo "*/10 *  * * *   root    $NEWNOSH/noshfax" >> $NOSHCRON
echo "*/1 *   * * *   root    $NEWNOSH/noshreminder" >> $NOSHCRON
echo "0 0     * * *   root    $NEWNOSH/noshbackup" >> $NOSHCRON
chown root.root $NOSHCRON
chmod 644 $NOSHCRON
log_only "Created NOSH ChartingSystem cron scripts."
# Set up SFTP
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
	log_only "Restarting SSH server service"
	$SSH >> $LOG 2>&1
	$SSH1 >> $LOG 2>&1
fi

# Install
if [ -d $NEWNOSH ]; then
	log_only "NOSH ChartingSystem already installed.  If this is an error, make sure that the $NEWNOSH directory does not exist."
	exit 0
else
	# New installation script
	if [ -f /etc/debian_version ]; then
		if [ -d /etc/php5/mods-available ]; then
			if ! [ -L /etc/php5/mods-available/mcrypt.ini ]; then
				ln -s /etc/php5/conf.d/mcrypt.ini /etc/php5/mods-available
				php5enmod mcrypt
				log_only "Enabled mycrpt module for PHP."
			fi
		fi
	else
		log_only "Ensure you have enabled the mcrypt module for PHP.  Check you distribution help pages to do this."
	fi
	if [ ! -f /usr/local/bin/composer ]; then
		curl -sS https://getcomposer.org/installer | php
		mv composer.phar /usr/local/bin/composer
	fi
	log_only "Installed composer.phar."
	if [ -d $NOSH_DIR ]; then
		log_only "The NOSH ChartingSystem documents directory already exists."
	else
		mkdir $NOSH_DIR
		log_only "The NOSH ChartingSystem documents directory has been created."
	fi
	chown -R $WEB_GROUP.$WEB_USER "$NOSH_DIR"
	chmod -R 755 $NOSH_DIR
	if ! [ -d "$NOSH_DIR"/scans ]; then
		mkdir "$NOSH_DIR"/scans
		chown -R $WEB_GROUP.$WEB_USER "$NOSH_DIR"/scans
		chmod -R 777 "$NOSH_DIR"/scans
	fi
	if ! [ -d "$NOSH_DIR"/received ]; then
		mkdir "$NOSH_DIR"/received
		chown -R $WEB_GROUP.$WEB_USER "$NOSH_DIR"/received
	fi
	if ! [ -d "$NOSH_DIR"/sentfax ]; then
		mkdir "$NOSH_DIR"/sentfax
		chown -R $WEB_GROUP.$WEB_USER "$NOSH_DIR"/sentfax
	fi
	log_only "The NOSH ChartingSystem scan and fax directories are secured."
	log_only "The NOSH ChartingSystem documents directory is secured."
	cd $NOSH_DIR
	composer create-project nosh-cs/nosh-cs --prefer-dist --stability dev
	# Create directory file
	touch $NOSHDIRFILE
	echo "$NOSH_DIR"/ >> $NOSHDIRFILE
	# Create .env file
	touch $NEWCONFIGDATABASE
	echo "<?php
	return array(
		'mysql_database' => '$MYSQL_DATABASE',
		'mysql_username' => '$MYSQL_USERNAME',
		'mysql_password' => '$MYSQL_PASSWORD'
	);" >> $NEWCONFIGDATABASE
	chown -R $WEB_GROUP.$WEB_USER $NEWNOSH
	chmod -R 755 $NEWNOSH
	chmod -R 777 $NEWNOSH/app/storage
	chmod -R 777 $NEWNOSH/public
	chmod 777 $NEWNOSH/noshfax
	chmod 777 $NEWNOSH/noshreminder
	chmod 777 $NEWNOSH/noshbackup
	log_only "Installed NOSH ChartingSystem core files."
	# If coming from old NOSH...
	if [ -d $OLDNOSH ]; then
		cp -nr /var/www/oldnosh/images/* $NEWNOSH/public/images/
		log_only "Copied previously created image files into new installation."
		cp -nr /var/www/oldnosh/received/* "$NOSH_DIR"/received/
		rm -rf "$NOSH_DIR"/received/thumbnails
		if [ "$(ls -A /var/www/oldnosh/sentfax)" ]; then
			cp -nr /var/www/oldnosh/sentfax/* "$NOSH_DIR"/sentfax/
		fi
		if [ "$(ls -A /var/www/oldnosh/scans)" ]; then
			cp -nr /var/www/oldnosh/scans/* "$NOSH_DIR"/scans/
		fi
		log_only "Copied previously created fax and scan files into new installation."
		log_only "The previous installation of NOSH is in Sthe directory /var/www/oldnosh as a precaution.  You can delete it manually at a later time."
	else
		echo "create database $MYSQL_DATABASE" | mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD
		cd $NEWNOSH
		php artisan migrate:install
		php artisan migrate
		log_only "Installed NOSH ChartingSystem database schema."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/templates.sql
		log_only "Installed NOSH ChartingSystem templates."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/orderslist1.sql
		log_only "Installed NOSH ChartingSystem order templates."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/meds_full.sql
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/meds_full_package.sql
		log_only "Installed NOSH ChartingSystem medication database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/supplements_list.sql
		log_only "Installed NOSH ChartingSystem supplements database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/icd9.sql
		log_only "Installed NOSH ChartingSystem ICD-9 database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/icd10.sql
		log_only "Installed NOSH ChartingSystem ICD-10 database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/cpt.sql
		log_only "Installed NOSH ChartingSystem CPT database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/cvx.sql
		log_only "Installed NOSH ChartingSystem immunization codes database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/gc.sql
		log_only "Installed NOSH ChartingSystem growth chart normalization values database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/lang.sql
		log_only "Installed NOSH ChartingSystem language database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/npi.sql
		log_only "Installed NOSH ChartingSystem NPI database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/pos.sql
		log_only "Installed NOSH ChartingSystem place of service database."
		mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DATABASE < $NEWNOSH/import/guardian_roles.sql
		log_only "Installed NOSH ChartingSystem guardian roles database."
	fi
	# Set up SSL and configuration file for Apache server
	if [ -f /etc/debian_version ]; then
		if ! [ -L /etc/apache2/sites-enabled/default-ssl ]; then
			log_only "Setting up Apache to use SSL using the default-ssl virtual host for Ubuntu/Debian."
			ln -s /etc/apache2/sites-available/default-ssl /etc/apache2/sites-enabled/default-ssl
		fi
		a2enmod ssl
		a2enmod rewrite
	else
		log_only "You will need to enable/create a virtual host and the SSL module for Apache before NOSH ChartingSystem will work securely."
	fi
	if [ -e "$WEB_CONF"/nosh.conf ]; then
		rm "$WEB_CONF"/nosh.conf
	fi
	touch "$WEB_CONF"/nosh.conf
	echo "Alias /nosh $NEWNOSH/public
<Directory $NEWNOSH/public>
	Options Indexes FollowSymLinks MultiViews
	AllowOverride All" >> "$WEB_CONF"/nosh.conf
	if [ "$APACHE_VER" = "4" ]; then
		echo "	Require all granted" >> "$WEB_CONF"/nosh.conf
	else
		echo "	Order allow,deny
	allow from all" >> "$WEB_CONF"/nosh.conf
	fi
	echo "	RewriteEngine On
	RewriteBase /nosh/
	# Redirect Trailing Slashes...
	RewriteRule ^(.*)/$ /$1 [L,R=301]

	# Handle Front Controller...
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^ index.php [L]
	<IfModule mod_php5.c>
		php_value upload_max_filesize 512M
		php_value post_max_size 512M
		php_flag magic_quotes_gpc off
		php_flag register_long_arrays off
	</IfModule>
</Directory>" >> "$WEB_CONF"/nosh.conf
	log_only "NOSH ChartingSystem Apache configuration file set."
	log_only "Restarting Apache service."
	$APACHE >> $LOG 2>&1
	# Installation completed
	log_only "You can now complete your new installation of NOSH ChartingSystem by browsing to:"
	log_only "https://localhost/nosh"
fi
exit 0
