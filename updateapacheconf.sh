#!/bin/sh
# update apache conf for nosh-cs to allow authentication headers

set -e

LOGDIR=/var/log/nosh
LOG=$LOGDIR/installation_log
log_only () {
	echo "$1"
	echo "`date`: $1" >> $LOG
}

# Check if running as root user
if [[ $EUID -ne 0 ]]; then
	echo "This script must be run as root.  Aborting." 1>&2
	exit 1
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
	elif [ -f /etc/redhat-release ]; then
		# CentOS or RHEL
		WEB_GROUP=apache
		WEB_GROUP=apache
		WEB_CONF=/etc/httpd/conf.d
		APACHE="/etc/init.d/httpd restart"
	elif [ -f /etc/arch-release ]; then
		# ARCH
		WEB_GROUP=http
		WEB_GROUP=http
		WEB_CONF=/etc/httpd/conf/extra
		APACHE="systemctl restart httpd.service"
	elif [ -f /etc/gentoo-release ]; then
		# Gentoo
		WEB_GROUP=apache
		WEB_GROUP=apache
		WEB_CONF=/etc/apache2/modules.d
		APACHE=/etc/init.d/apache2
	elif [ -f /etc/fedora-release ]; then
		# Fedora
		WEB_GROUP=apache
		WEB_GROUP=apache
		WEB_CONF=/etc/httpd/conf.d
		APACHE="/etc/init.d/httpd restart"
	fi
elif [[ "$OSTYPE" == "darwin"* ]]; then
	# Mac
	WEB_GROUP=_www
	WEB_GROUP=_www
	WEB_CONF=/etc/httpd/conf.d
	APACHE="/usr/sbin/apachectl restart"
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
else
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
fi
# Check apache version
APACHE_VER=$(apache2 -v | awk -F"[..]" 'NR<2{print $2}')

read -e -p "Enter the directory where NOSH ChartingSystem patient files is stored: " -i "/noshdocuments" NOSH_DIR
if [ "$NOSH_DIR" = "" ]; then
	echo "The NOSH ChartingSystem documents directory cannot be blank.  Aborting." 1>&2
	exit 1
elif [ ! -d $NOSH_DIR ]; then
	echo "The NOSH ChartingSystem documents directory does not exist.  Check your entry.  Aborting." 1>&2
	exit 1
else
	NEWNOSH=$NOSH_DIR/nosh-cs
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
	RewriteRule ^ - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
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
fi
exit 0
