
/* loads the SNOMED CT 'Full' release - replace filenames with relevant locations of base SNOMED CT release files*/

use nosh;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_Concept_Full_INT_date.txt' 
	into table curr_concept_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_Description_Full-en_INT_date.txt' 
	into table curr_description_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_TextDefinition_Full-en_INT_date.txt' 
	into table curr_textdefinition_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_Relationship_Full_INT_date.txt' 
	into table curr_relationship_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Terminology/sct2_StatedRelationship_Full_INT_date.txt' 
	into table curr_stated_relationship_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Language/der2_cRefset_LanguageFull-en_INT_date.txt' 
	into table curr_langrefset_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Content/der2_cRefset_AssociationReferenceFull_INT_date.txt' 
	into table curr_associationrefset_d
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Content/der2_cRefset_AttributeValueFull_INT_date.txt' 
	into table curr_attributevaluerefset_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Map/der2_sRefset_SimpleMapFull_INT_date.txt' 
	into table curr_simplemaprefset_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Content/der2_Refset_SimpleFull_INT_date.txt' 
	into table curr_simplerefset_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

load data local 
	infile '/var/www/nosh/extensions/snomed/RF2Release/Full/Refset/Map/der2_iissscRefset_ComplexMapFull_INT_date.txt' 
	into table curr_complexmaprefset_f
	columns terminated by '\t' 
	lines terminated by '\r\n' 
	ignore 1 lines;

























