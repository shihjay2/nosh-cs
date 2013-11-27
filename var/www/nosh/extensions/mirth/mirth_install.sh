#!/bin/bash
# Install script for MirthConnect in conjunction with nosh-cs

set -e
read -e -p "Enter the username for the Mirth database:" -i "mirthuser" MIRTH_USER
read -e -p "Enter the password for the Mirth database:" -i "mirthpass" MIRTH_PASS
read -e -p "Enter the MySQL username:" -i "root" MYSQL_USER
read -e -p "Enter the MySQL password:" -i "" MYSQL_PASS
read -e -p "Enter the directory where MirthConnect will be installed:" -i "/opt/mirthconnect" DIR

# Constants and paths
LOG=/var/log/mirth_installation_log
CONF=$DIR/conf/mirth.properties
INIT=/etc/init.d/mcservice
START=$DIR/mcservice

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

collect_conf () {
	echo `grep -i "^[;[:space:]]*$1[[:space:]=]" $CONF | cut -d \= -f 2 | cut -d \; -f 1 | sed 's%[ 	M]%%gi'`
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
	wget http://downloads.mirthcorp.com/connect/2.2.1.5861.b1248/mirthconnect-2.2.1.5861.b1248-unix.sh
	log_only "MirthConnect downloaded."
	sh mirthconnect-2.2.1.5861.b1248-unix.sh
	log_only "MirthConnect installed."
	touch mirth.sql
	echo "CREATE DATABASE mirthdb DEFAULT CHARACTER SET utf8;" >> mirth.sql
	echo "GRANT ALL ON mirthdb.* TO $MIRTH_USER@'localhost' IDENTIFIED BY '$MIRTH_PASS' WITH GRANT OPTION;" >> mirth.sql
	echo "GRANT ALL ON mirthdb.* TO $MIRTH_USER@'%' IDENTIFIED BY '$MIRTH_PASS' WITH GRANT OPTION;" >> mirth.sql
	mysql -u $MYSQL_USER -p$MYSQL_PASS < mirth.sql
	log_only "Mirth database created in MySQL."
	touch /etc/init/mcservice.conf
	echo 'description     "mcservice"' >> /etc/init/mcservice.conf
	echo 'author          "Michael Chen <shihjay2@gmail.com>"' >> /etc/init/mcservice.conf
	echo 'start on started mountall' >> /etc/init/mcservice.conf
	echo 'stop on shutdown' >> /etc/init/mcservice.conf
	echo 'respawn' >> /etc/init/mcservice.conf
	echo 'respawn limit 99 5' >> /etc/init/mcservice.conf
	echo "env HOME=$DIR" >> /etc/init/mcservice.conf
	echo "exec $DIR/mcservice start-launchd" >> /etc/init/mcservice.conf
	log_only "MirthConnect Service installed."
	invoke-rc.d mcservice stop >> $LOG 2>&1

	# Collect variables from Mirth configuration file
	KEY1="database"
	VALUE1=$(collect_conf "$KEY1")
	KEY2="database.url"
	VALUE2=$(collect_conf "$KEY2")
	KEY3="database.username"
	VALUE3=$(collect_conf "$KEY3")
	KEY4="database.password"
	VALUE4=$(collect_conf "$KEY4")

	# Backup the configuration file before modification
	cp $CONF $CONF.BAK
	log_only "A backup of your Mirth configuration has been created at $CONF.BAK."

	# Modify pertinent php.ini variables
	FLAG_ON=0
	process_conf () {
		if [ "$3" -eq "1" ]; then
			# Make recommendations to php.ini
			if [ "$FLAG_ON" -eq "0" ]; then
				log_only "The following setting(s) have been modified in the Mirth configuration file at $CONF :"
			fi      
			FLAG_ON=1
		else
			# Modify php.ini
			sed -i "s%^[; 	]*$1[ 	=].*$%$1 = $2%" $CONF
			log_only "Successfully set $1 = $2"  
		fi
	}
	for i in `seq 1 2`; do
		if [ "$VALUE1" != "mysql" ]; then
			process_conf "$KEY1" "mysql" $i
		fi
		if [ "$VALUE2" != "jdbc:mysql://localhost:3306/mirthdb" ]; then
			process_conf "$KEY2" "jdbc:mysql://localhost:3306/mirthdb" $i
		fi
		if [ "$VALUE3" != "$MIRTH_USER" ]; then
			process_conf "$KEY3" "$MIRTH_USER" $i
		fi
		if [ "$VALUE4" != "$MIRTH_PASS" ]; then
			process_conf "$KEY4" "$MIRTH_PASS" $i
		fi
		if [ "$FLAG_ON" -eq "0" ]; then
			log_only "The Mirth configuration file is set."
			break
		fi
	done
	rm -rf mirthconnect-2.2.1.5861.b1248-unix.sh
	rm -rf mirth.sql
	log_only "Removed temporary files."
	invoke-rc.d mcservice start >> $LOG 2>&1
	log_only "You can now administer Mirth by browsing to: http://localhost:8080."
	exit 0
else
	echo "Exiting...Wrong version of the JAVA runtime environment.  You must install the Oracle JAVA JRE before proceeding."
	exit 1
fi
