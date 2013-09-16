-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 14, 2012 at 10:01 AM
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
-- Table structure for table `supplements_list`
--

CREATE TABLE IF NOT EXISTS `supplements_list` (
  `supplements_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplement_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`supplements_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

--
-- Dumping data for table `supplements_list`
--

INSERT INTO `supplements_list` (`supplements_id`, `supplement_name`) VALUES
(1, 'Acai'),
(2, 'Alfalfa'),
(3, 'Aloe'),
(4, 'Aloe Vera'),
(5, 'Aristolochic Acids'),
(6, 'Asian Ginseng'),
(7, 'Astragalus'),
(8, 'Bacillus coagulans '),
(9, 'Belladonna'),
(10, 'Beta-carotene'),
(11, 'Bifidobacteria '),
(12, 'Bilberry'),
(13, 'Biotin'),
(14, 'Bitter Orange'),
(15, 'Black Cohosh'),
(16, 'Black psyllium'),
(17, 'Black tea'),
(18, 'Bladderwrack'),
(19, 'Blessed thistle'),
(20, 'Blond psyllium'),
(21, 'Blueberry'),
(22, 'Blue-green algae'),
(23, 'Boron'),
(24, 'Bromelain'),
(25, 'Calcium'),
(26, 'Calendula'),
(27, 'Cancell/Cantron/Protocel (PDQ)'),
(28, 'Cartilage (Bovine and Shark) (PDQ)'),
(29, 'Cassia cinnamon'),
(30, 'Cat''s Claw'),
(31, 'Chamomile'),
(32, 'Chondroitin sulfate'),
(33, 'Chromium'),
(34, 'Clove'),
(35, 'Coenzyme Q-10'),
(36, 'Coenzyme Q10 (PDQ)'),
(37, 'Colloidal Silver Products'),
(38, 'Cranberry'),
(39, 'Creatine'),
(40, 'Dandelion'),
(41, 'Devil''s claw'),
(42, 'DHEA'),
(43, 'Dong quai'),
(44, 'Echinacea'),
(45, 'Essiac/Flor-Essence (PDQ)'),
(46, 'Eucalyptus'),
(47, 'European Elder (Elderberry)'),
(48, 'European Mistletoe'),
(49, 'Evening Primrose Oil'),
(50, 'Fenugreek'),
(51, 'Feverfew'),
(52, 'Fish oil'),
(53, 'Flaxseed'),
(54, 'Flaxseed and Flaxseed Oil'),
(55, 'Flaxseed oil'),
(56, 'Folate'),
(57, 'Folic acid'),
(58, 'Garlic'),
(59, 'Ginger'),
(60, 'Gingko'),
(61, 'Ginkgo'),
(62, 'Ginseng, American'),
(63, 'Ginseng, Panax'),
(64, 'Ginseng, Siberian'),
(65, 'Glucosamine hydrochloride'),
(66, 'Glucosamine sulfate'),
(67, 'Goldenseal'),
(68, 'Grape Seed Extract'),
(69, 'Green Tea'),
(70, 'Hawthorn'),
(71, 'Hoodia'),
(72, 'Horse Chestnut'),
(73, 'Horsetail'),
(74, 'Hydrazine Sulfate (PDQ)'),
(75, 'Iodine'),
(76, 'Iron'),
(77, 'Kava'),
(78, 'Lactobacillus'),
(79, 'Laetrile/Amygdalin (PDQ)'),
(80, 'L-arginine'),
(81, 'Lavender'),
(82, 'Licorice'),
(83, 'Licorice Root'),
(84, 'Lycium '),
(85, 'Lycopene'),
(86, 'Magnesium'),
(87, 'Manganese'),
(88, 'Melatonin'),
(89, 'Milk Thistle'),
(90, 'Milk Thistle (PDQ)'),
(91, 'Mistletoe Extracts (PDQ)'),
(92, 'Niacin and niacinamide (Vitamin B3) '),
(93, 'Noni'),
(94, 'Oral Probiotics'),
(95, 'Pantothenic acid (Vitamin B5)'),
(96, 'Passionflower'),
(97, 'PC-SPES (PDQ)'),
(98, 'Pennyroyal'),
(99, 'Peppermint'),
(100, 'Peppermint Oil'),
(101, 'Phosphate salts'),
(102, 'Pomegranate'),
(103, 'Propolis'),
(104, 'Pycnogenol'),
(105, 'Pyridoxine (Vitamin B6)'),
(106, 'Red Clover'),
(107, 'Red yeast'),
(108, 'Riboflavin (Vitamin B2)'),
(109, 'Roman chamomile'),
(110, 'Saccharomyces boulardii '),
(111, 'Sage'),
(112, 'SAMe'),
(113, 'Saw Palmetto'),
(114, 'Selected Vegetables/Sun''s Soup (PDQ)'),
(115, 'Selenium'),
(116, 'Senna'),
(117, 'Shark cartilage'),
(118, 'Soy'),
(119, 'St. John''s Wort'),
(120, 'Tea Tree Oil'),
(121, 'Thiamine (Vitamin B1)'),
(122, 'Thunder God Vine'),
(123, 'Turmeric'),
(124, 'Valerian'),
(125, 'Vitamin A'),
(126, 'Vitamin A and Carotenoids'),
(127, 'Vitamin B12'),
(128, 'Vitamin B6'),
(129, 'Vitamin C'),
(130, 'Vitamin C (Ascorbic acid)'),
(131, 'Vitamin D'),
(132, 'Vitamin E'),
(133, 'Vitamin K'),
(134, 'Wild yam'),
(135, 'Yohimbe'),
(136, 'Zinc'),
(137, '5-HTP');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
