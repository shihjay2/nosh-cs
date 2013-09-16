-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 14, 2012 at 08:17 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nosh`
--

-- --------------------------------------------------------

--
-- Table structure for table `cpt`
--

CREATE TABLE IF NOT EXISTS `cpt` (
  `cpt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cpt` varchar(255) DEFAULT NULL,
  `cpt_description` longtext,
  `cpt_charge` varchar(255) DEFAULT NULL,
  `cpt_common` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`cpt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14262 ;

--
-- Dumping data for table `cpt`
--

INSERT INTO `cpt` (`cpt_id`, `cpt`, `cpt_description`, `cpt_charge`, `cpt_common`) VALUES
(14222, '99201', 'Office or other outpatient visit for the evaluation and management of a new patient, which requires these three key components: a problem focused history; a problem focused examination; straightforward medical decision making. counseling and/or coordination of care with other providers or agencies are provided consistent with the nature of the problem(s) and the patient''s and/or family''s needs. usually, the presenting problem(s) are self limited or minor. physicians typically spend 10 minutes face-to-face with the patient and/or family.\r', NULL, NULL),
(14223, '99202', 'Office or other outpatient visit for the evaluation and management of a new patient, which requires three key components, usually, the presenting problem(s) are of low to moderate severity.\r', NULL, NULL),
(14224, '99203', 'Office or other outpatient visit for the evaluation and management of a new patient, which requires three key components, usually, the presenting problem(s) are of moderate severity.\r', NULL, NULL),
(14225, '99204', 'Office or other outpatient visit for the evaluation and management of a new patient, which requires three key components, usually, the presenting problem(s) are of moderate to high severity\r', NULL, NULL),
(14226, '99205', 'Office or other outpatient visit for the evaluation and management of a new patient, which requires these three key components: a comprehensive history; a comprehensive examination; medical decision making of high complexity. counseling and/or coordination of care with other providers or agencies are provided consistent with the nature of the problem(s) and the patient''s and/or family''s needs. usually, the presenting problem(s) are of moderate to high severity. physicians typically spend 60 minutes face-to-face with the patient and/or family.\r', NULL, NULL),
(14227, '99211', 'Office or other outpatient visit for the evaluation and management of an established patient, that may not require the presence of a physician. usually, the presenting problem(s) are minimal. typically, 5 minutes are spent performing or supervising these services.\r', NULL, NULL),
(14228, '99212', 'Office or other outpatient visit for the evaluation and management of an established patient, which requires at least two key components, usually, the presenting problem(s) are self limited or minor.\r', NULL, NULL),
(14229, '99213', 'Office or other outpatient visit for the evaluation and management of an established patient, which requires at least two key components, usually, the presenting problem(s) are of low to moderate severity\r', NULL, NULL),
(14230, '99214', 'Office or other outpatient visit for the evaluation and management of an established patient, which requires at least two key components, usually, the presenting problem(s) are of moderate to high severity.\r', NULL, NULL),
(14231, '99215', 'Office or other outpatient visit for the evaluation and management of an established patient, which requires at least two of these three key components: a comprehensive history; a comprehensive examination; medical decision making of high complexity. counseling and/or coordination of care with other providers or agencies are provided consistent with the nature of the problem(s) and the patient''s and/or family''s needs. usually, the presenting problem(s) are of moderate to high severity. physicians typically spend 40 minutes face-to-face with the patient and/or family.\r', NULL, NULL),
(7699, '90676', 'Rabies vaccine, for intradermal use\r', NULL, NULL),
(7700, '90680', 'Rotavirus vaccine, pentavalent, 3 dose schedule, live, for oral use\r', NULL, NULL),
(7701, '90690', 'Typhoid vaccine, live, oral\r', NULL, NULL),
(7702, '90691', 'Typhoid vaccine, vi capsular polysaccharide (vicps), for intramuscular use\r', NULL, NULL),
(7703, '90692', 'Typhoid vaccine, heat- and phenol-inactivated (h-p), for subcutaneous or intradermal use\r', NULL, NULL),
(7704, '90693', 'Typhoid vaccine, acetone-killed, dried (akd), for subcutaneous use (u.s. military)\r', NULL, NULL),
(7705, '90698', 'Diphtheria, tetanus toxoids, acellular pertussis vaccine, haemophilus influenza type b, and poliovirus vaccine, inactivated (dtap - hib - ipv), for intramuscular use\r', NULL, NULL),
(7706, '90701', 'Diphtheria, tetanus toxoids, and whole cell pertussis vaccine (dtp), for intramuscular use\r', NULL, NULL),
(7709, '90704', 'Mumps virus vaccine, live, for subcutaneous use\r', NULL, NULL),
(7710, '90705', 'Measles virus vaccine, live, for subcutaneous use\r', NULL, NULL),
(7711, '90706', 'Rubella virus vaccine, live, for subcutaneous use\r', NULL, NULL),
(7712, '90708', 'Measles and rubella virus vaccine, live, for subcutaneous use\r', NULL, NULL),
(7713, '90710', 'Measles, mumps, rubella, and varicella vaccine (mmrv), live, for subcutaneous use\r', NULL, NULL),
(7714, '90712', 'Poliovirus vaccine, (any type[s]) (opv), live, for oral use\r', NULL, NULL),
(7716, '90715', 'Tetanus, diphtheria toxoids and acellular pertussis vaccine (tdap), when administered to individuals 7 years or older, for intramuscular use\r', NULL, NULL),
(7717, '90716', 'Varicella virus vaccine, live, for subcutaneous use\r', NULL, NULL),
(7718, '90717', 'Yellow fever vaccine, live, for subcutaneous use\r', NULL, NULL),
(7720, '90720', 'Diphtheria, tetanus toxoids, and whole cell pertussis vaccine and hemophilus influenza b vaccine (dtp-hib), for intramuscular use\r', NULL, NULL),
(7721, '90721', 'Diphtheria, tetanus toxoids, and acellular pertussis vaccine and hemophilus influenza b vaccine (dtap-hib), for intramuscular use\r', NULL, NULL),
(7722, '90723', 'Diphtheria, tetanus toxoids, acellular pertussis vaccine, hepatitis b, and poliovirus vaccine, inactivated (dtap-hepb-ipv), for intramuscular use\r', NULL, NULL),
(7723, '90725', 'Cholera vaccine for injectable use\r', NULL, NULL),
(7724, '90727', 'Plague vaccine, for intramuscular use\r', NULL, NULL),
(7725, '90732', 'Pneumococcal polysaccharide vaccine, 23-valent, adult or immunosuppressed patient dosage, when administered to individuals 2 years or older, for subcutaneous or intramuscular use\r', NULL, NULL),
(7726, '90733', 'Meningococcal polysaccharide vaccine (any group(s)), for subcutaneous use\r', NULL, NULL),
(7727, '90734', 'Meningococcal conjugate vaccine, serogroups a, c, y and w-135 (tetravalent), for intramuscular use\r', NULL, NULL),
(7728, '90735', 'Japanese encephalitis virus vaccine, for subcutaneous use\r', NULL, NULL),
(7729, '90736', 'Zoster (shingles) vaccine, live, for subcutaneous injection\r', NULL, NULL),
(7730, '90740', 'Hepatitis b vaccine, dialysis or immunosuppressed patient dosage (3 dose schedule), for intramuscular use\r', NULL, NULL),
(7731, '90743', 'Hepatitis b vaccine, adolescent (2 dose schedule), for intramuscular use\r', NULL, NULL),
(7732, '90747', 'Hepatitis b vaccine, dialysis or immunosuppressed patient dosage (4 dose schedule), for intramuscular use\r', NULL, NULL),
(7733, '90748', 'Hepatitis b and hemophilus influenza b vaccine (hepb-hib), for intramuscular use\r', NULL, NULL),
(7734, '90749', 'Unlisted vaccine/toxoid\r', NULL, NULL),
(10923, 'G0008', 'Administration of influenza virus vaccine Carrier judgment\r', NULL, NULL),
(10924, 'G0009', 'Administration of pneumococcal vaccine Carrier judgment\r', NULL, NULL),
(10925, 'G0010', 'Administration of hepatitis B vaccine Carrier judgment\r', NULL, NULL),
(11050, 'G0377', 'Administration of vaccine for part d drug Carrier judgment\r', NULL, NULL),
(11131, 'G8108', 'Patient documented to have received influenza vaccination during influenza season Carrier judgment\r', NULL, NULL),
(11132, 'G8109', 'Patient not documented to have received influenza vaccination during influenza season Carrier judgment\r', NULL, NULL),
(11133, 'G8110', 'Clinician documented that patient was not an eligible candidate for influenza vaccination measure Carrier judgment\r', NULL, NULL),
(11138, 'G8115', 'Patient documented to have received pneumococcal vaccination Carrier judgment\r', NULL, NULL),
(11139, 'G8116', 'Patient not documented to have received pneumococcal vaccination Carrier judgment\r', NULL, NULL),
(11140, 'G8117', 'Clinician documented that patient was not an eligible candidate for pneumococcal vaccination measure Carrier judgment\r', NULL, NULL),
(11274, 'G8423', 'Documented that patient was screened and either influenza vaccination status is current or patient was counseled Carrier judgment\r', NULL, NULL),
(11275, 'G8424', 'Influenza vaccine status was not screened Carrier judgment\r', NULL, NULL),
(11276, 'G8425', 'Influenza vaccine status screened, patient not current and counseling was not provided Carrier judgment\r', NULL, NULL),
(11277, 'G8426', 'Documented that patient was not appropriate for screening and/or counseling about the influenza vaccine (e.g., allergy to eggs) Carrier judgment\r', NULL, NULL),
(11908, 'J3530', 'Nasal vaccine inhalation Special coverage instructions apply\r', NULL, NULL),
(13423, 'S0195', 'Pneumococcal conjugate vaccine, polyvalent, intramuscular, for children from five years to nine years of age who have not previously received the vaccine Not payable by Medicare\r', NULL, NULL),
(14212, '90632', 'Hepatitis a vaccine, adult dosage, for intramuscular use\r', NULL, NULL),
(14213, '90633', 'Hepatitis a vaccine, pediatric/adolescent dosage-2 dose schedule, for intramuscular use\r', NULL, NULL),
(14214, '90645', 'Hemophilus influenza b vaccine (hib), hboc conjugate (4 dose schedule), for intramuscular use\r', NULL, NULL),
(14215, '90669', 'Pneumococcal conjugate vaccine, polyvalent, for children under 5 years, for intramuscular use\r', NULL, NULL),
(14216, '90700', 'Diphtheria, tetanus toxoids, and acellular pertussis vaccine (dtap), for use in individuals younger than 7 years, for intramuscular use\r', NULL, NULL),
(14217, '90707', 'Measles, mumps and rubella virus vaccine (mmr), live, for subcutaneous use\r', NULL, NULL),
(14218, '90713', 'Poliovirus vaccine, inactivated, (ipv), for subcutaneous or intramuscular use\r', NULL, NULL),
(14220, '90744', 'Hepatitis b vaccine, pediatric/adolescent dosage (3 dose schedule), for intramuscular use\r', NULL, NULL),
(14221, '90746', 'Hepatitis b vaccine, adult dosage, for intramuscular use\r', NULL, NULL),
(14261, '90670', 'Pneumococcal conjugate vaccine, polyvalent, for children under 5 years, for intramuscular use', NULL, NULL),
(278, '10061', 'Incision and drainage of abscess (eg, carbuncle, suppurative hidradenitis, cutaneous or subcutaneous abscess, cyst, furuncle, or paronychia); complicated or multiple\r', NULL, NULL),
(386, '12011', 'Simple repair of superficial wounds of face, ears, eyelids, nose, lips and/or mucous membranes; 2.5 cm or less\r', NULL, NULL),
(7127, '86480', 'Tuberculosis test, cell mediated immunity measurement of gamma interferon antigen response\r', NULL, NULL),
(7465, '87880', 'Infectious agent detection by immunoassay with direct optical observation; streptococcus, group a\r', NULL, NULL),
(8559, '99345', 'Home visit for the evaluation and management of a new patient, which requires these 3 key components: a comprehensive history; a comprehensive examination; and medical decision making of high complexity. counseling and/or coordination of care with other providers or agencies are provided consistent with the nature of the problem(s) and the patient''s and/or family''s needs. usually, the patient is unstable or has developed a significant new problem requiring immediate physician attention. physicians typically spend 75 minutes face-to-face with the patient and/or family.\r', NULL, NULL),
(14190, '11200', 'Removal of skin tags, multiple fibrocutaneous tags, any area; up to and including 15 lesions\r', NULL, NULL),
(14197, '11404', 'Excision, benign lesion including margins, except skin tag (unless listed elsewhere), trunk, arms or legs; excised diameter 3.1 to 4.0 cm\r', NULL, NULL),
(14198, '11730', 'Avulsion of nail plate, partial or complete, simple; single\r', NULL, NULL),
(14201, '17110', 'Destruction (eg, laser surgery, electrosurgery, cryosurgery, chemosurgery, surgical curettement), of flat warts, molluscum contagiosum, or milia; up to 14 lesions\r', NULL, NULL),
(14206, '81002', 'Urinalysis, by dip stick or tablet reagent for bilirubin, glucose, hemoglobin, ketones, leukocytes, nitrite, ph, protein, specific gravity, urobilinogen, any number of these constituents; non-automated, without microscopy\r', NULL, NULL),
(14207, '81025', 'Urine pregnancy test, by visual color comparison methods\r', NULL, NULL),
(14240, '99348', 'Home visit for the evaluation and management of an established patient, which requires at least two of three key components.  Usually, the presenting problem(s) are of low to moderate severity.\r', NULL, NULL),
(14241, '99349', 'Home visit for the evaluation and management of an established patient, which requires at least two of these three key components: a detailed interval history; a detailed examination; medical decision making of moderate complexity. counseling and/or coordination of care with other providers or agencies are provided consistent with the nature of the problem(s) and the patient''s and/or family''s needs. usually, the presenting problem(s) are moderate to high severity. physicians typically spend 40 minutes face-to-face with the patient and/or family.\r', NULL, NULL),
(14242, '99354', 'Prolonged physician service in the office or other outpatient setting requiring direct (face-to-face) patient contact beyond the usual service.\r', NULL, NULL),
(14243, '99381', 'Initial comprehensive preventive medicine evaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, new patient; infant (age under 1 year)\r', NULL, NULL),
(14244, '99382', 'Initial comprehensive preventive medicine evaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, new patient; early childhood (age 1 through 4 years)\r', NULL, NULL),
(14248, '99386', 'Initial comprehensive preventive medicine evaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, new patient; 40-64 years\r', NULL, NULL),
(14249, '99391', 'Periodic comprehensive preventive medicine reevaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, established patient; infant (age under 1 year)\r', NULL, NULL),
(14250, '99392', 'Periodic comprehensive preventive medicine reevaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, established patient; early childhood (age 1 through 4 years)\r', NULL, NULL),
(14251, '99393', 'Periodic comprehensive preventive medicine reevaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, established patient; late childhood (age 5 through 11 years)\r', NULL, NULL),
(14252, '99394', 'Periodic comprehensive preventive medicine reevaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, established patient; adolescent (age 12 through 17 years)\r', NULL, NULL),
(14253, '99395', 'Periodic comprehensive preventive medicine reevaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, established patient; 18-39 years\r', NULL, NULL),
(14254, '99396', 'Periodic comprehensive preventive medicine reevaluation and management of an individual including an age and gender appropriate history, examination, counseling/anticipatory guidance/risk factor reduction interventions, and the ordering of appropriate immunization(s), laboratory/diagnostic procedures, established patient; 40-64 years\r', NULL, NULL),
(14255, '99605', 'Medication therapy management service(s) provided by a pharmacist, individual, face-to-face with patient, with assessment and intervention if provided; initial 15 minutes, new patient\r', NULL, NULL),
(14256, '99606', 'Medication therapy management service(s) provided by a pharmacist, individual, face-to-face with patient, with assessment and intervention if provided; initial 15 minutes, established patient\r', NULL, NULL),
(14257, '99607', 'Medication therapy management service(s) provided by a pharmacist, individual, face-to-face with patient, with assessment and intervention if provided; each additional 15 minutes\r', NULL, NULL),
(14258, '99355', 'Prolonged physician service in the office or other outpatient setting requiring direct (face-to-face) patient contact beyond the usual service; each additional 30 minutes (list separately in addition to code for prolonged physician service)\r', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
