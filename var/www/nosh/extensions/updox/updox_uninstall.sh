#!/bin/bash
# Uninstall script for Updox Sync in conjunction with nosh-cs

set -e
read -e -p "Enter the username of Ubuntu OS account that Updox Sync was set up: " USER
# Constants and paths
LOG=/var/log/updox_installation_log
CONF=/home/$USER/connector.properties
UPDOX=/home/$USER/updox

log_only () {
	echo "$1"
	echo "`date`: $1" >> $LOG
}

if [[ $EUID -ne 0 ]]; then
	echo "This script must be run as root" 1>&2
	exit 1
fi
/usr/bin/gpasswd -d $USER www-data
log_only "Removed user from Apache group."
log_only "Removed Updox Sync from startup."
rm -rf $UPDOX
rm -rf /etc/cron.d/updox
rm -rf $CONF
log_only "Removed Updox Sync web app directory and configuration files."
exit 0
