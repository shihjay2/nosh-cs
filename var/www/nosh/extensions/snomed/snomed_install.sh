#!/bin/bash
# Install script for SNOMED-CT in conjunction with nosh-cs

set -e
read -e -p "Enter the MySQL username:" -i "root" MYSQL_USER
read -e -p "Enter the MySQL password:" -i "" MYSQL_PASS
read -e -p "Enter the date of the release (last 8 digits of the zip file):" -i "" DATE

# Constants and paths

CONCEPT=/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_Concept_Full_INT_$DATE.txt
DESCRIPTION=/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_Description_Full-en_INT_$DATE.txt
DEFINITION=/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_TextDefinition_Full-en_INT_$DATE.txt
RELATIONSHIP=/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_Relationship_Full_INT_$DATE.txt
STATED=/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_StatedRelationship_Full_INT_$DATE.txt
LANGUAGE=/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Language/der2_cRefset_LanguageFull-en_INT_$DATE.txt
ASSOCIATION=/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Content/der2_cRefset_AssociationReferenceFull_INT_$DATE.txt
ATTRIBUTE=/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Content/der2_cRefset_AttributeValueFull_INT_$DATE.txt
MAP=/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Map/der2_sRefset_SimpleMapFull_INT_$DATE.txt
SIMPLE=/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Content/der2_Refset_SimpleFull_INT_$DATE.txt
COMPLEX=/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Map/der2_iissscRefset_ComplexMapFull_INT_$DATE.txt
LOG=/var/log/snomed_installation_log
LOAD=/var/www/nosh/extensions/snomed/load.sql
LOADNEW=/var/www/nosh/extensions/snomed/load_$DATE.sql

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

if [ ! -f $CONCEPT ]; then
	echo "$CONCEPT does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $DESCRIPTION ]; then
	echo "$DESCRIPTION does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $DEFINITION ]; then
	echo "$DEFINITION does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $RELATIONSHIP ]; then
	echo "$RELATIONSHIP does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $STATED ]; then
	echo "$STATED does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $LANGUAGE ]; then
	echo "$LANGUAGE does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $ASSOCIATION ]; then
	echo "$ASSOCIATION does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $ATTRIBUTE ]; then
	echo "$ATTRIBUTE does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $MAP ]; then
	echo "$MAP does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $SIMPLE ]; then
	echo "$SIMPLE does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi

if [ ! -f $COMPLEX ]; then
	echo "$COMPLEX does not exist.  Check if the file is in the correct directory and the date entered is correct." 1>&2
	exit 1
fi
mysql -u $MYSQL_USER -p$MYSQL_PASS nosh < environment.sql
log_only "SNOMED tables created in NOSH MySQL database."
sed 's|date|'"$DATE"'|g' "$LOAD" > "$LOADNEW"
mysql -u $MYSQL_USER -p$MYSQL_PASS --local-infile=1 nosh < $LOADNEW
log_only "Data imported successfully."
rm -rf $LOADNEW
