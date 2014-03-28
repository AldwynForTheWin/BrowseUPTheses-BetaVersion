-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2014 at 02:40 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `browseuptheses`
--
CREATE DATABASE IF NOT EXISTS `browseuptheses` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `browseuptheses`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_username` varchar(255) DEFAULT NULL,
  `admin_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_username`, `admin_password`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) DEFAULT NULL,
  `counter` int(11) NOT NULL DEFAULT '1',
  `last_search` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `counter`, `last_search`) VALUES
(1, 'Art and Architecture', 1, '2014-03-28 13:11:48'),
(2, 'Communication', 1, '2014-03-28 13:11:48'),
(3, 'Economics and Business', 1, '2014-03-28 13:11:48'),
(4, 'Education', 1, '2014-03-28 13:11:48'),
(5, 'History', 1, '2014-03-28 13:11:48'),
(6, 'Law', 1, '2014-03-28 13:11:48'),
(7, 'Literature', 1, '2014-03-28 13:11:48'),
(8, 'Music and Performing Arts', 1, '2014-03-28 13:11:48'),
(9, 'Philosophy', 3, '2014-03-28 06:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(255) DEFAULT NULL,
  `tel` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`department_id`),
  KEY `faculty_id` (`faculty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `dep_name`, `tel`, `email`, `faculty_id`) VALUES
(1, 'Computer Science', NULL, NULL, 7),
(2, 'Mass Communication', NULL, NULL, NULL),
(3, 'Fine Arts', NULL, NULL, NULL),
(4, 'Management', NULL, NULL, NULL),
(5, 'Political Science', NULL, NULL, NULL),
(6, 'Psychology', NULL, NULL, NULL),
(7, 'Biology', NULL, NULL, NULL),
(8, 'Mathematics', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE IF NOT EXISTS `faculty` (
  `fac_username` varchar(255) DEFAULT NULL,
  `fac_password` varchar(255) DEFAULT NULL,
  `faculty_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`faculty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`fac_username`, `fac_password`, `faculty_id`) VALUES
('kurtpumares', 'kurt1', 7),
('melissaricks', 'matt', 9),
('UPCFACULTY27', 'F7FDdW', 27),
('UPCFACULTY28', 'TFZHf6', 28),
('UPCFACULTY30', 'G69tdl', 30),
('UPCFACULTY31', 'UoJnrW', 31),
('UPCFACULTY32', 'Jh0YaF', 32),
('UPCFACULTY35', 'gq7kF4', 35),
('UPCFACULTY36', 'zgUELv', 36),
('UPCFACULTY37', 'uSLfmn', 37),
('UPCFACULTY38', 'UYCp3C', 38);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_profiles`
--

CREATE TABLE IF NOT EXISTS `faculty_profiles` (
  `faculty_id` int(11) NOT NULL,
  `fac_fname` varchar(255) DEFAULT NULL,
  `fac_lname` varchar(255) DEFAULT NULL,
  `fac_mname` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  PRIMARY KEY (`faculty_id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faculty_profiles`
--

INSERT INTO `faculty_profiles` (`faculty_id`, `fac_fname`, `fac_lname`, `fac_mname`, `department_id`, `gender`) VALUES
(7, 'Kurt Junshean', 'Espinosa', 'Brevis', 6, 'M'),
(9, 'Matt', 'Evans', 'Tiger', 6, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `researcher`
--

CREATE TABLE IF NOT EXISTS `researcher` (
  `researcher_id` int(11) NOT NULL AUTO_INCREMENT,
  `res_lname` varchar(255) DEFAULT NULL,
  `res_fname` varchar(255) DEFAULT NULL,
  `res_mname` varchar(255) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`researcher_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `researcher`
--

INSERT INTO `researcher` (`researcher_id`, `res_lname`, `res_fname`, `res_mname`, `course_id`) VALUES
(1, 'Macabalig- fuday', 'Wiwyn', 'O.', NULL),
(2, 'Albuquerque', 'Alfonso', 'O.', NULL),
(3, 'Macabalig- fuday', 'Another', 'O.', NULL),
(4, 'Macabalig- fuday', 'Alfonso', 'O.', NULL),
(5, 'Macabalig- fuday', 'Regine', 'O.', NULL),
(6, 'Macabalig- ehem', 'Alfonso', 'O.', NULL),
(7, 'Macabalig- trala', 'Potsoy', 'O.', NULL),
(8, 'Shongatong', 'Shonga', 'T.', NULL),
(9, 'Foucault', 'Michel', 'O.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag_name`) VALUES
(1, 'polymorphism'),
(2, 'inheritance'),
(3, 'spain'),
(4, 'history'),
(5, 'homeostasis'),
(6, 'adaptation'),
(7, 'foucault'),
(8, 'self'),
(9, 'politics'),
(10, 'sex'),
(11, 'sickness'),
(12, 'anti-discrimination');

-- --------------------------------------------------------

--
-- Table structure for table `thesis`
--

CREATE TABLE IF NOT EXISTS `thesis` (
  `thesis_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `abstract` text,
  `date_published` timestamp NULL DEFAULT NULL,
  `date_accessioned` timestamp NULL DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`thesis_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `thesis`
--

INSERT INTO `thesis` (`thesis_id`, `title`, `abstract`, `date_published`, `date_accessioned`, `date_added`) VALUES
(19, 'An Analysis on the Analysts'' Analytical Anus', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\n										    	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\n										    	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\n										    	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\n										    	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\n										    	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2014-03-22 08:33:50'),
(21, 'UP Cebu''s Online Theses Archive', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\n										    	tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\n										    	quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\n										    	consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\n										    	cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\n										    	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2014-03-11 16:00:00', '2014-03-11 16:00:00', '2014-03-27 03:18:43'),
(24, 'The Politics of the Personal in Michel Foucault', 'Foucaultâ€™s scope of mastery has always been covered in the power of relations, specifically to the formation and strengthening the concept of differentiating the human world into equilibrium of things (e.g. the healthy & the unhealthy, the poor & the rich, etc.). He further noted that the specification of homosexuality was closely interwoven with the gradual emersion of a â€˜bourgeoisie sexualityâ€™, (i.e. which differentiates itself from upper and lower categories of sexuality).\r\nHomosexuality has been deemed to be â€˜medically disqualifiedâ€™; but though this notion made gay people fight back on behalf of their sexuality, it doesnâ€™t mean that it does not cost a lot of hazard. Foucault argued that no matter how comfortably the homosexual sits within the skin of his own identity, he never stops to be a changeable disoriented individual in his role and identity. Being gay signifies that these choices carry themselves transversely their entire life; hence, it purports that sexual choices are at the same time moulders of the ways of life and a drift for ones lifeâ€™s change of existence.\r\nAn individual is marked by his own individuality, attaches him to his identity, and lays on a law of recognizable truths about himself â€“ and that is bio-power. The individualâ€™s capacity to twist loose from the historical form by which his life must appear to have already been shaped; as for Foucault, â€œno one is ever a form always identical to itself, but a function, endlessly produced, and is irresistibly changing.â€\r\nThe concept of the â€œself as a work of artâ€ elaborates much of a technology of the self that directs the self to social and political fields through dialogical interactions with others, it can partake in the endless renaissance and maintenance of a social and political status quo â€“ as in ontology of the present and of ourselves. It paves way to the idea of perpetually rediscovering oneself though self-in-dialogical-encounter instead of self-as-origin internalization (i.e. means no social and political interaction with others).\r\nIt is not to identify the psychological traits and the visible marks of the homosexual, but it is to try to define and develop a way of life.  As for Foucault, â€œit is not about who we truly are, or about what our sexuality finally dictates, but it is on what we want to be and could be.â€ As for homosexual asceticism, â€œthe target is not to discover who we are, but to refuse what we are because we have to promote new forms of subjectivity through the refusal of this kind of individuality.â€\r\nEscaping as much as possible from the types of relations the society proposes for the entire gay group and trying to create an empty space where we are new relational possibilities, is the best way homosexual liberation groups could always do.', '1987-09-07 16:00:00', '1988-09-07 16:00:00', '2014-03-27 14:20:13'),
(25, 'The Sexual Politics of Sickness', 'The general theory implied (as it is likewise implied and believed scientifically by most physicians during the 19th century) in this text was that: Women are weak, dependent, and diseased by nature and their normal state was to be sick all the time. The theory of invalidism of women in the society in the late 19th century became universal. As such, there are so-many-to-mention testimonials (of course women) about the incognito syndrome communicating between females in the old times, making it more discriminating for women during that times that they have this stereotypic common disease â€“ their femininity. It is also explicit to the journals of this testimonial women that they are "tired of suffering" and they want to "get out of that life". These sufferings don''t only pertain to physical diagnostic conditions like dyspepsia, hysteria, feebleness, etc., but also it reveals the emotional side of women that they want to be not just parasites to men and not dependent upon the opposite sex.\r\nMoreover, femininity is considered a disorder since it has its nature of coping up with the hazards of menstruation, pregnancy, tuberculosis, (as they say) and of course industrial diseases. It is also suggested that women naturally donâ€™t take a leap of success, since they â€˜devolveâ€™; men naturally evolve within his environment with expertness and specializations to all fields of interest. It says that as time passes by, men are consequently differentiating while women are progressively de-differentiating.\r\nWhen it comes to the duel between sexes, the psychology on the mysterious epidemic within females also has something to do with the reproductive organs they biologically have (e.g. ovaries, uterus). Ovaries, alongside with clitoris, are perceived to be the ones which controls the personality of women and as such, they are much related to the psychological disorders of women (yet they are sure signs of ovarian disease). An assumption has been argued that women would be better ones and would be relieved from genital pains if and only if they are going to undergo ovariotomy or clitoridectomy.\r\nIn the battle between the organs of both sexes, men were urged to represent the brain and had to fight back sexual indulgence for the purpose of conserving all his energy for his professional being; while women are seen to be sperm-draining vampires who leaves men weak, spent and effeminate. Also, women has been perceived as the child-bearing creation of God, so they have to collect all their energy downward towards their womb. The rest cure for women as to get themselves out of their mysterious internal pain must be intervened with total isolation and sensory deprivation by the doctors, not just on soft foods or massages as treatments.', '1974-09-04 16:00:00', '1995-06-22 16:00:00', '2014-03-27 14:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `thesis_categories`
--

CREATE TABLE IF NOT EXISTS `thesis_categories` (
  `thesis_id` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`thesis_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thesis_categories`
--

INSERT INTO `thesis_categories` (`thesis_id`, `category_id`) VALUES
(19, 1),
(21, 1),
(24, 2),
(19, 3),
(21, 3),
(24, 3),
(25, 3),
(24, 4),
(25, 4),
(19, 5),
(21, 5),
(24, 5),
(24, 6),
(19, 7),
(21, 7),
(24, 7),
(25, 7),
(19, 9),
(21, 9),
(24, 9),
(25, 9);

-- --------------------------------------------------------

--
-- Table structure for table `thesis_department`
--

CREATE TABLE IF NOT EXISTS `thesis_department` (
  `thesis_id` int(11) NOT NULL DEFAULT '0',
  `department_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`thesis_id`,`department_id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thesis_department`
--

INSERT INTO `thesis_department` (`thesis_id`, `department_id`) VALUES
(19, 1),
(21, 1),
(24, 5),
(25, 6);

-- --------------------------------------------------------

--
-- Table structure for table `thesis_faculty`
--

CREATE TABLE IF NOT EXISTS `thesis_faculty` (
  `thesis_id` int(11) NOT NULL DEFAULT '0',
  `faculty_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`thesis_id`,`faculty_id`),
  KEY `faculty_id` (`faculty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thesis_faculty`
--

INSERT INTO `thesis_faculty` (`thesis_id`, `faculty_id`) VALUES
(21, 7),
(24, 9),
(25, 9),
(19, 27);

-- --------------------------------------------------------

--
-- Table structure for table `thesis_researchers`
--

CREATE TABLE IF NOT EXISTS `thesis_researchers` (
  `thesis_id` int(11) NOT NULL DEFAULT '0',
  `researcher_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`thesis_id`,`researcher_id`),
  KEY `researcher_id` (`researcher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thesis_researchers`
--

INSERT INTO `thesis_researchers` (`thesis_id`, `researcher_id`) VALUES
(19, 1),
(21, 1),
(24, 1),
(25, 9);

-- --------------------------------------------------------

--
-- Table structure for table `thesis_tags`
--

CREATE TABLE IF NOT EXISTS `thesis_tags` (
  `thesis_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  KEY `tag_id` (`tag_id`),
  KEY `thesis_id` (`thesis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thesis_tags`
--

INSERT INTO `thesis_tags` (`thesis_id`, `tag_id`) VALUES
(19, 1),
(19, 2),
(21, 1),
(21, 2),
(24, 7),
(24, 8),
(24, 9),
(25, 10),
(25, 9),
(25, 11),
(25, 7),
(25, 8),
(25, 12);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faculty_profiles`
--
ALTER TABLE `faculty_profiles`
  ADD CONSTRAINT `faculty_profiles_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `faculty_profiles_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `researcher`
--
ALTER TABLE `researcher`
  ADD CONSTRAINT `researcher_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thesis_categories`
--
ALTER TABLE `thesis_categories`
  ADD CONSTRAINT `thesis_categories_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `thesis` (`thesis_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thesis_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thesis_department`
--
ALTER TABLE `thesis_department`
  ADD CONSTRAINT `thesis_department_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `thesis` (`thesis_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thesis_department_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thesis_faculty`
--
ALTER TABLE `thesis_faculty`
  ADD CONSTRAINT `thesis_faculty_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `thesis` (`thesis_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thesis_faculty_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thesis_researchers`
--
ALTER TABLE `thesis_researchers`
  ADD CONSTRAINT `thesis_researchers_ibfk_1` FOREIGN KEY (`thesis_id`) REFERENCES `thesis` (`thesis_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thesis_researchers_ibfk_2` FOREIGN KEY (`researcher_id`) REFERENCES `researcher` (`researcher_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thesis_tags`
--
ALTER TABLE `thesis_tags`
  ADD CONSTRAINT `thesis_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `thesis_tags_ibfk_2` FOREIGN KEY (`thesis_id`) REFERENCES `thesis` (`thesis_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
