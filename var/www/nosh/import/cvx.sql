-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 14, 2012 at 09:34 AM
-- Server version: 5.5.28
-- PHP Version: 5.4.6-1ubuntu1.1

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
-- Table structure for table `cvx`
--

CREATE TABLE IF NOT EXISTS `cvx` (
  `cvx_code` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cvx`
--

INSERT INTO `cvx` (`cvx_code`, `description`, `vaccine_name`) VALUES
(143, 'Adenovirus types 4 and 7', 'Adenovirus, type 4 and type 7, live, oral'),
(54, 'Adenovirus, type 4', 'Adenovirus vaccine, type 4, live, oral'),
(55, 'Adenovirus, type 7', 'Adenovirus vaccine, type 7, live, oral'),
(82, 'Adenovirus, unspecified formulation', 'Adenovirus vaccine, unspecified formulation'),
(24, 'Anthrax', 'Anthrax vaccine'),
(19, 'BCG', 'Bacillus Calmette-Guerin vaccine'),
(27, 'Botulinum antitoxin', 'Botulinum antitoxin'),
(26, 'Cholera', 'Cholera vaccine'),
(29, 'CMVIG', 'Cytomegalovirus immune globulin, intravenous'),
(56, 'Dengue fever', 'Dengue fever vaccine'),
(12, 'Diphtheria antitoxin', 'Diphtheria antitoxin'),
(28, 'DT (pediatric)', 'Diphtheria and tetanus toxoids, adsorbed for pediatric use'),
(20, 'DTaP', 'Diphtheria, tetanus toxoids and acellular pertussis vaccine'),
(106, 'DTaP, 5 pertussis antigens', 'Diphtheria, tetanus toxoids and acellular pertussis vaccine, 5 pertussis antigens'),
(107, 'DTaP, unspecified formulation', 'Diphtheria, tetanus toxoids and acellular pertussis vaccine, unspecified formulation'),
(146, 'DTaP,IPV,Hib,HepB', 'Diphtheria and Tetanus Toxoids and Acellular Pertussis Adsorbed, Inactivated Poliovirus, Haemophilus b Conjugate (Meningococcal Outer Membrane Protein Complex), and Hepatitis B (Recombinant) Vaccine.'),
(110, 'DTaP-Hep B-IPV', 'DTaP-hepatitis B and poliovirus vaccine'),
(50, 'DTaP-Hib', 'DTaP-Haemophilus influenzae type b conjugate vaccine'),
(120, 'DTaP-Hib-IPV', 'Diphtheria, tetanus toxoids and acellular pertussis vaccine, Haemophilus influenzae type b conjugate, and poliovirus vaccine, inactivated (DTaP-Hib-IPV)'),
(130, 'DTaP-IPV', 'Diphtheria, tetanus toxoids and acellular pertussis vaccine, and poliovirus vaccine, inactivated'),
(132, 'DTaP-IPV-HIB-HEP B, historical', 'Historical record of vaccine containing\n    * diphtheria, tetanus toxoids and acellular pertussis,\n    * poliovirus, inactivated,\n    * Haemophilus influenzae type b conjugate,\n    * Hepatitis B (DTaP-Hib-IPV)'),
(1, 'DTP', 'Diphtheria, tetanus toxoids and pertussis vaccine'),
(22, 'DTP-Hib', 'DTP-Haemophilus influenzae type b conjugate vaccine'),
(102, 'DTP-Hib-Hep B', 'DTP- Haemophilus influenzae type b conjugate and hepatitis b vaccine'),
(57, 'Hantavirus', 'Hantavirus vaccine'),
(30, 'HBIG', 'Hepatitis B immune globulin'),
(52, 'Hep A, adult', 'Hepatitis A vaccine, adult dosage'),
(83, 'Hep A, ped/adol, 2 dose', 'Hepatitis A vaccine, pediatric/adolescent dosage, 2 dose schedule'),
(84, 'Hep A, ped/adol, 3 dose', 'Hepatitis A vaccine, pediatric/adolescent dosage, 3 dose schedule'),
(31, 'Hep A, pediatric, unspecified formulation', 'Hepatitis A vaccine, pediatric dosage, unspecified formulation'),
(85, 'Hep A, unspecified formulation', 'Hepatitis A vaccine, unspecified formulation'),
(104, 'Hep A-Hep B', 'Hepatitis A and hepatitis B vaccine'),
(8, 'Hep B, adolescent or pediatric', 'Hepatitis B vaccine, pediatric or pediatric/adolescent dosage'),
(42, 'Hep B, adolescent/high risk infant', 'Hepatitis B vaccine, adolescent/high risk infant dosage'),
(43, 'Hep B, adult', 'Hepatitis B vaccine, adult dosage'),
(44, 'Hep B, dialysis', 'Hepatitis B vaccine, dialysis patient dosage'),
(45, 'Hep B, unspecified formulation', 'Hepatitis B vaccine, unspecified formulation'),
(58, 'Hep C', 'Hepatitis C vaccine'),
(59, 'Hep E', 'Hepatitis E vaccine'),
(60, 'Herpes simplex 2', 'Herpes simplex virus, type 2 vaccine'),
(47, 'Hib (HbOC)', 'Haemophilus influenzae type b vaccine, HbOC conjugate'),
(46, 'Hib (PRP-D)', 'Haemophilus influenzae type b vaccine, PRP-D conjugate'),
(49, 'Hib (PRP-OMP)', 'Haemophilus influenzae type b vaccine, PRP-OMP conjugate'),
(48, 'Hib (PRP-T)', 'Haemophilus influenzae type b vaccine, PRP-T conjugate'),
(17, 'Hib, unspecified formulation', 'Haemophilus influenzae type b vaccine, conjugate unspecified formulation'),
(51, 'Hib-Hep B', 'Haemophilus influenzae type b conjugate and Hepatitis B vaccine'),
(61, 'HIV', 'Human immunodeficiency virus vaccine'),
(118, 'HPV, bivalent', 'Human papilloma virus vaccine, bivalent'),
(62, 'HPV, quadrivalent', 'Human papilloma virus vaccine, quadrivalent'),
(137, 'HPV, unspecified formulation', 'HPV, unspecified formulation'),
(86, 'IG', 'Immune globulin, intramuscular'),
(14, 'IG, unspecified formulation', 'Immune globulin, unspecified formulation'),
(87, 'IGIV', 'Immune globulin, intravenous'),
(123, 'Influenza, H5N1-1203', 'Influenza virus vaccine, H5N1, A/Vietnam/1203/2004 (national stockpile)'),
(135, 'Influenza, high dose seasonal', 'Influenza, high dose seasonal, preservative-free'),
(111, 'Influenza, live, intranasal', 'Influenza virus vaccine, live, attenuated, for intranasal use'),
(149, 'Influenza, live, intranasal, quadrivalent', 'Influenza, live, intranasal, quadrivalent'),
(141, 'Influenza, seasonal, injectable', 'Influenza, seasonal, injectable'),
(140, 'Influenza, seasonal, injectable, preservative free', 'Influenza, seasonal, injectable, preservative free'),
(144, 'Influenza, seasonal, intradermal, preservative free', 'Seasonal influenza, intradermal, preservative free'),
(15, 'Influenza, split (incl. purified surface antigen)', 'Influenza virus vaccine, split virus (incl. purified surface antigen)-retired CODE'),
(88, 'Influenza, unspecified formulation', 'Influenza virus vaccine, unspecified formulation'),
(16, 'Influenza, whole', 'Influenza virus vaccine, whole virus'),
(10, 'IPV', 'Poliovirus vaccine, inactivated'),
(134, 'Japanese Encephalitis IM', 'Japanese Encephalitis vaccine for intramuscular administration'),
(39, 'Japanese encephalitis SC', 'Japanese Encephalitis Vaccine SC'),
(129, 'Japanese Encephalitis, unspecified formulation', 'Japanese Encephalitis vaccine, unspecified formulation'),
(63, 'Junin virus', 'Junin virus vaccine'),
(64, 'Leishmaniasis', 'Leishmaniasis vaccine'),
(65, 'Leprosy', 'Leprosy vaccine'),
(66, 'Lyme disease', 'Lyme disease vaccine'),
(4, 'M/R', 'Measles and rubella virus vaccine'),
(67, 'Malaria', 'Malaria vaccine'),
(5, 'Measles', 'Measles virus vaccine'),
(68, 'Melanoma', 'Melanoma vaccine'),
(103, 'Meningococcal C conjugate', 'Meningococcal C conjugate vaccine'),
(148, 'Meningococcal C/Y-HIB PRP', 'Meningococcal Groups C and Y and Haemophilus b Tetanus Toxoid Conjugate Vaccine'),
(147, 'Meningococcal MCV4, unspecified formulation', 'Meningococcal, MCV4, unspecified formulation(groups A, C, Y and W-135)'),
(136, 'Meningococcal MCV4O', 'Meningococcal oligosaccharide (groups A, C, Y and W-135) diphtheria toxoid conjugate vaccine (MCV4O)'),
(114, 'Meningococcal MCV4P', 'Meningococcal polysaccharide (groups A, C, Y and W-135) diphtheria toxoid conjugate vaccine (MCV4P)'),
(32, 'Meningococcal MPSV4', 'Meningococcal polysaccharide vaccine (MPSV4)'),
(108, 'Meningococcal, unspecified formulation', 'Meningococcal vaccine, unspecified formulation'),
(3, 'MMR', 'Measles, mumps and rubella virus vaccine'),
(94, 'MMRV', 'Measles, mumps, rubella, and varicella virus vaccine'),
(7, 'Mumps', 'Mumps virus vaccine'),
(127, 'Novel influenza-H1N1-09', 'Novel influenza-H1N1-09, injectable'),
(128, 'Novel Influenza-H1N1-09, all formulations', 'Novel influenza-H1N1-09, all formulations'),
(125, 'Novel Influenza-H1N1-09, nasal', 'Novel Influenza-H1N1-09, live virus for nasal administration'),
(126, 'Novel influenza-H1N1-09, preservative-free', 'Novel influenza-H1N1-09, preservative-free, injectable'),
(2, 'OPV', 'Poliovirus vaccine, live, oral'),
(69, 'Parainfluenza-3', 'Parainfluenza-3 virus vaccine'),
(11, 'Pertussis', 'Pertussis vaccine'),
(23, 'Plague', 'Plague vaccine'),
(133, 'Pneumococcal conjugate PCV 13', 'Pneumococcal conjugate vaccine, 13 valent'),
(100, 'Pneumococcal conjugate PCV 7', 'Pneumococcal conjugate vaccine, 7 valent'),
(33, 'Pneumococcal polysaccharide PPV23', 'Pneumococcal polysaccharide vaccine, 23 valent'),
(109, 'Pneumococcal, unspecified formulation', 'Pneumococcal vaccine, unspecified formulation'),
(89, 'Polio, unspecified formulation', 'Poliovirus vaccine, unspecified formulation'),
(70, 'Q fever', 'Q fever vaccine'),
(40, 'Rabies, intradermal injection', 'Rabies vaccine, for intradermal injection'),
(18, 'Rabies, intramuscular injection', 'Rabies vaccine, for intramuscular injection'),
(90, 'Rabies, unspecified formulation', 'Rabies vaccine, unspecified formulation'),
(72, 'Rheumatic fever', 'Rheumatic fever vaccine'),
(73, 'Rift Valley fever', 'Rift Valley fever vaccine'),
(34, 'RIG', 'Rabies immune globulin'),
(119, 'Rotavirus, monovalent', 'Rotavirus, live, monovalent vaccine'),
(116, 'Rotavirus, pentavalent', 'Rotavirus, live, pentavalent vaccine'),
(74, 'Rotavirus, tetravalent', 'Rotavirus, live, tetravalent vaccine'),
(122, 'Rotavirus, unspecified formulation', 'Rotavirus vaccine, unspecified formulation'),
(71, 'RSV-IGIV', 'Respiratory syncytial virus immune globulin, intravenous'),
(93, 'RSV-MAb', 'Respiratory syncytial virus monoclonal antibody (palivizumab), intramuscular'),
(145, 'RSV-MAb (new)', 'Respiratory syncytial virus monoclonal antibody (motavizumab), intramuscular'),
(6, 'Rubella', 'Rubella virus vaccine'),
(38, 'Rubella/mumps', 'Rubella and mumps virus vaccine'),
(76, 'Staphylococcus bacterio lysate', 'Staphylococcus bacteriophage lysate'),
(138, 'Td (adult)', 'Tetanus and diphtheria toxoids, not adsorbed, for adult use'),
(113, 'Td (adult) preservative free', 'Tetanus and diphtheria toxoids, adsorbed, preservative free, for adult use'),
(9, 'Td (adult), adsorbed', 'Tetanus and diphtheria toxoids, adsorbed, for adult use'),
(139, 'Td(adult) unspecified formulation', 'Td(adult) unspecified formulation'),
(115, 'Tdap', 'Tetanus toxoid, reduced diphtheria toxoid, and acellular pertussis vaccine, adsorbed'),
(35, 'Tetanus toxoid, adsorbed', 'Tetanus toxoid, adsorbed'),
(142, 'Tetanus toxoid, not adsorbed', 'Tetanus toxoid, not adsorbed'),
(112, 'Tetanus toxoid, unspecified formulation', 'Tetanus toxoid, unspecified formulation'),
(77, 'Tick-borne encephalitis', 'Tick-borne encephalitis vaccine'),
(13, 'TIG', 'Tetanus immune globulin'),
(98, 'TST, unspecified formulation', 'Tuberculin skin test; unspecified formulation'),
(95, 'TST-OT tine test', 'Tuberculin skin test; old tuberculin, multipuncture device'),
(96, 'TST-PPD intradermal', 'Tuberculin skin test; purified protein derivative solution, intradermal'),
(97, 'TST-PPD tine test', 'Tuberculin skin test; purified protein derivative, multipuncture device'),
(78, 'Tularemia vaccine', 'Tularemia vaccine'),
(25, 'Typhoid, oral', 'Typhoid vaccine, live, oral'),
(41, 'Typhoid, parenteral', 'Typhoid vaccine, parenteral, other than acetone-killed, dried'),
(53, 'Typhoid, parenteral, AKD (U.S. military)', 'Typhoid vaccine, parenteral, acetone-killed, dried (U.S. military)'),
(91, 'Typhoid, unspecified formulation', 'Typhoid vaccine, unspecified formulation'),
(101, 'Typhoid, ViCPs', 'Typhoid Vi capsular polysaccharide vaccine'),
(131, 'Typhus, historical', 'Historical record of a typhus vaccination'),
(75, 'Vaccinia (smallpox)', 'Vaccinia (smallpox) vaccine'),
(105, 'Vaccinia (smallpox) diluted', 'Vaccinia (smallpox) vaccine, diluted'),
(79, 'Vaccinia immune globulin', 'Vaccinia immune globulin'),
(21, 'Varicella', 'Varicella virus vaccine'),
(81, 'VEE, inactivated', 'Venezuelan equine encephalitis, inactivated'),
(80, 'VEE, live', 'Venezuelan equine encephalitis, live, attenuated'),
(92, 'VEE, unspecified formulation', 'Venezuelan equine encephalitis vaccine, unspecified formulation'),
(36, 'VZIG', 'Varicella zoster immune globulin'),
(117, 'VZIG (IND)', 'Varicella zoster immune globulin (Investigational New Drug)'),
(37, 'Yellow fever', 'Yellow fever vaccine'),
(121, 'Zoster', 'Zoster vaccine, live'),
(998, ' no vaccine administered', 'No vaccine administered'),
(99, ' RESERVED - do not use', 'RESERVED - do not use'),
(999, ' unknown', 'Unknown vaccine or immune globulin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
