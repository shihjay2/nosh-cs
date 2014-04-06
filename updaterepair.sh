#!/bin/sh
# update-repair script for nosh-cs

set -e

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
	elif [ -f /etc/redhat-release ]; then
		# CentOS or RHEL
		WEB_GROUP=apache
		WEB_GROUP=apache
	elif [ -f /etc/arch-release ]; then
		# ARCH
		WEB_GROUP=http
		WEB_GROUP=http
	elif [ -f /etc/gentoo-release ]; then
		# Gentoo
		WEB_GROUP=apache
		WEB_GROUP=apache
	elif [ -f /etc/fedora-release ]; then
		# Fedora
		WEB_GROUP=apache
		WEB_GROUP=apache
	fi
elif [[ "$OSTYPE" == "darwin"* ]]; then
	# Mac
	WEB_GROUP=_www
	WEB_GROUP=_www
elif [[ "$OSTYPE" == "cygwin" ]]; then
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
elif [[ "$OSTYPE" == "win32" ]]; then
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
elif [[ "$OSTYPE" == "freebsd"* ]]; then
	WEB_GROUP=www
	WEB_GROUP=www
else
	echo "This operating system is not supported by this install script at this time.  Aborting." 1>&2
	exit 1
fi
read -e -p "Enter the directory where NOSH ChartingSystem patient files is stored: " -i "/noshdocuments" NOSH_DIR
if [ "$NOSH_DIR" = "" ]; then
	echo "The NOSH ChartingSystem documents directory cannot be blank.  Aborting." 1>&2
	exit 1
else
	NOSH=$NOSH_DIR/nosh-cs/app/controllers
	NOSHSCRIPT=$NOSH/BackupController.php
fi
# Make backup
mv $NOSHSCRIPT $NOSHSCRIPT.bak
# Obtain file from GitHub
cd $NOSH
wget https://raw.github.com/shihjay2/nosh-core/master/app/controllers/BackupController.php
chown $WEB_GROUP.$WEB_USER $NOSHSCRIPT
chmod 755 $NOSHSCRIPT
echo "Your update script is repaired."
