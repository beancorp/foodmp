-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 19, 2014 at 08:37 AM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `foodmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `promo_email_template`
--

DROP TABLE IF EXISTS `promo_email_template`;
CREATE TABLE IF NOT EXISTS `promo_email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `key_name` varchar(50) DEFAULT NULL,
  `content` text,
  `variables` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `promo_email_template`
--

INSERT INTO `promo_email_template` (`id`, `name`, `key_name`, `content`, `variables`) VALUES
(1, 'Email to top 1 winner', 'email_to_top1', 'Hi ##username##,&amp;nbsp;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;Congratulation for winning top 1&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;Thank you.&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##'),
(2, 'Email to top 97 winner', 'email_to_top97', 'Hi  ##username## ,  win top97&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##'),
(3, 'Email to top 2 winner', 'email_to_top2', 'Hi  ##username## ,  win top2 &lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##'),
(4, 'Email to top 3 winner', 'email_to_top3', 'Hi ##username##, win top3. thank you&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##'),
(5, 'Email to admin to state top 3 winner of the month', 'email_admin_top3', 'Hi admin, here are winners : ##list_username##&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##list_username##'),
(6, 'Email to appprove entry', 'email_approved', '\n\n&lt;title&gt;Approve entry&lt;/title&gt;\n\n\n&lt;p&gt;Hi &lt;strong&gt; ##username## &lt;/strong&gt; , your entry is approved&lt;/p&gt;\n&lt;table&gt;\n&lt;tbody&gt;&lt;tr&gt;\n&lt;th&gt;Firstname&lt;/th&gt;\n&lt;th&gt;Lastname&lt;/th&gt;\n&lt;/tr&gt;\n&lt;tr&gt;\n&lt;td&gt;John&lt;/td&gt;\n&lt;td&gt;Doe&lt;/td&gt;\n&lt;/tr&gt;\n&lt;/tbody&gt;&lt;/table&gt;&lt;br /&gt;\n\n&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##'),
(7, 'Email reject entry', 'email_reject', 'Hi ##username## , your entry is removed&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;\n\n\n&lt;strong&gt;tes Html email&lt;/strong&gt;\n&lt;div&gt;&lt;strong&gt;&lt;br /&gt;&lt;/strong&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##'),
(8, 'Email thank you for enter competiton', 'email_thank_you', 'Hi ##username##, thank you for entering competition&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;\n\n\n\n\n&lt;h1&gt;Thank You Email &lt;/h1&gt;\n\n&lt;p&gt;Thank you for entering this competition&lt;/p&gt;\n\n&lt;i&gt;htm test email&lt;/i&gt;\n\n\n', '##username##'),
(9, 'Email to admin to inform new entry', 'email_new_entry', '\n\n&lt;title&gt;New entry&lt;/title&gt;\n\n\n&lt;p&gt;Hi &lt;strong&gt;Hi admin, new entry created ##link_new_entry##&lt;/strong&gt;&lt;/p&gt;\n&lt;table&gt;\n&lt;tbody&gt;&lt;tr&gt;\n&lt;th&gt;Firstname&lt;/th&gt;\n&lt;th&gt;Lastname&lt;/th&gt;\n&lt;/tr&gt;\n&lt;tr&gt;\n&lt;td&gt;John&lt;/td&gt;\n&lt;td&gt;Doe&lt;/td&gt;\n&lt;/tr&gt;\n&lt;/tbody&gt;&lt;/table&gt;&lt;br /&gt;\n\n&lt;div&gt;&lt;span style=&quot;text-decoration: underline; font-style: italic;&quot;&gt;New entry created here&lt;/span&gt;&lt;/div&gt;', '##link_new_entry##'),
(10, 'Email to admin to inform winner of Grand', 'email_admin_grand_winner', 'Hi admin, This is winner of Grand ##list_grand_winner##\n\n&lt;html&gt;\n&lt;strong&gt;Test mail&lt;/strong&gt;\n&lt;/html&gt;', '##list_grand_winner##'),
(11, 'Email to admin to inform duplicate code', 'email_admin_duplicate_code', 'Hi Admin\n\nSome code is duplicate with user\n\n##username## \n\nCode: ##code##\n\nTime: ##time##&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;br /&gt;&lt;/div&gt;\n\n\n&lt;strong&gt;Test mail&lt;/strong&gt;\n&lt;div&gt;&lt;strong&gt;&lt;br /&gt;&lt;/strong&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##username##,  ##code##, ##time##'),
(12, 'Email to admin to state top 97 winner of the month', 'email_admin_top97', 'Hi admin, here are 97 winners of the month: ##list_username##&lt;div&gt;&lt;br /&gt;&lt;/div&gt;&lt;div&gt;&lt;div&gt;&amp;lt;html&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;strong&amp;gt;Test mail&amp;lt;/strong&amp;gt;&lt;/div&gt;&lt;div&gt;&amp;lt;/html&amp;gt;&lt;/div&gt;&lt;/div&gt;', '##list_username##');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
