#!/bin/bash
# Uninstall script for MirthConnect in conjunction with nosh-cs

set -e
# Constants and paths
LOG=/var/log/mirth_installation_log

log_only () {
	echo "$1"
	echo "`date`: $1" >> $LOG
}

if [[ $EUID -ne 0 ]]; then
	echo "This script must be run as root" 1>&2
	exit 1
fi
invoke-rc.d mcservice stop >> $LOG 2>&1
log_only "Stopped MirthConnect Service."
read -e -p "Enter the MySQL username:" -i "root" MYSQL_USER
read -e -p "Enter the MySQL password:" -i "" MYSQL_PASS
read -e -p "Enter the directory where MirthConnect was installed:" -i "/opt/mirthconnect" DIR
$DIR/uninstall
log_only "Completed MirthConnect uninstall program."
mysqladmin -f -u$MYSQL_USER -p$MYSQL_PASS drop "mirthdb" >> $LOG 2>&1
log_only "Removed Mirth MySQL database."
if [ -d $DIR ]; then
	rm -rf $DIR
fi
START=$DIR/mcservice
sed -i -e 's|'"$START"' start||' /etc/rc.local
sed -i -e '/^$/ d' /etc/rc.local
log_only "Removed MirthConnect Service files."
exit 0
