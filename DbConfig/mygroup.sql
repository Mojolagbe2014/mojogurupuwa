-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2017 at 05:41 PM
-- Server version: 5.6.31
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mygroup`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` varchar(100) NOT NULL,
  `date_registered` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `username`, `password`, `role`, `date_registered`) VALUES
(1, 'Mojolagbe Jamiu Babatunde', 'mojolagbe@gmail.com', 'Babatunde', 'ae2b1fca515949e5d54fb22b8ed95575', 'Sub-Admin', '2015-08-20'),
(2, 'Vladimir Okhmatovski', 'vladimir.okhmatovski@umanitoba.ca', 'Admin', 'ae2b1fca515949e5d54fb22b8ed95575', 'Admin', '2015-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(300) NOT NULL,
  `image` varchar(300) NOT NULL,
  `date_time` varchar(300) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `name`, `description`, `location`, `image`, `date_time`, `status`, `date_added`) VALUES
(1, 'Website Launch', '<p><span style="color:rgb(92, 101, 102); font-family:open sans; font-size:14px">The website was redesigned by <a href="http://kaisteventures.com">Kaiste Ventures Limited.</a></span></p>\r\n', 'Ketu, Lagos, Nigeria', '574060_website_launch.jpg', '2016/03/25 20:00', 1, '2015-11-13 13:13:25'),
(2, 'Let starts this', '<p>I don&#39;t know what is gonna happen here</p>\r\n', 'Winnipeg', '456694_let_starts_this.jpg', '2017/04/15 16:00', 0, '2017-04-15 12:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL,
  `question` varchar(700) NOT NULL,
  `answer` text NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `date_added`) VALUES
(2, 'tets', 'tets', '2017-04-15');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `program` varchar(100) NOT NULL,
  `field` varchar(200) NOT NULL,
  `bio` text NOT NULL,
  `email` varchar(300) NOT NULL,
  `website` varchar(500) NOT NULL,
  `picture` varchar(300) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  `graduated` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `name`, `program`, `field`, `bio`, `email`, `website`, `picture`, `visible`, `graduated`) VALUES
(4, 'Jamiu Babatunde Mojolagbe', 'M.Sc', 'Computational Electromagnetics', '<p>Testing&nbsp;</p>\r\n', 'mojolagm@myumanitoba.ca', 'http://www.umanitoba.ca', '397343_jamiu_babatunde_mojolagbe.jpg', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `patent`
--

CREATE TABLE IF NOT EXISTS `patent` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `issuance_date` date NOT NULL,
  `image` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patent`
--

INSERT INTO `patent` (`id`, `name`, `description`, `issuance_date`, `image`) VALUES
(1, 'System and Method for Remote and Mobile Patient Monitoring Service Using Heterogeneous Wireless Access Network', 'US Patent 9,007,908/Patent Application 12/573,581,', '2017-04-25', '552629_.png'),
(2, 'PotashCorp Project', 'US Patent 9,007,908/Patent Application 12/573,581', '2017-04-10', '748504_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `presentation`
--

CREATE TABLE IF NOT EXISTS `presentation` (
  `id` int(11) NOT NULL,
  `name` varchar(600) NOT NULL,
  `organizer` varchar(200) NOT NULL,
  `location` tinytext NOT NULL,
  `date_presented` date NOT NULL,
  `description` text NOT NULL,
  `media` varchar(600) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_registered` date NOT NULL,
  `image` varchar(300) NOT NULL,
  `featured` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `presentation`
--

INSERT INTO `presentation` (`id`, `name`, `organizer`, `location`, `date_presented`, `description`, `media`, `status`, `date_registered`, `image`, `featured`) VALUES
(1, 'Business Strategy', 'Department of Agricultural Sciences', 'E2-563 EITC', '2017-04-18', '<p>Presentation description goes here</p>\r\n', '206020_2017_04_18.pdf', 1, '2017-04-14', '873278_department_of_agricultural_sciences.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL,
  `name` varchar(600) NOT NULL,
  `is_completed` varchar(10) NOT NULL,
  `sponsor` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text NOT NULL,
  `media` varchar(600) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_registered` date NOT NULL,
  `image` varchar(300) NOT NULL,
  `featured` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `is_completed`, `sponsor`, `start_date`, `end_date`, `description`, `media`, `status`, `date_registered`, `image`, `featured`) VALUES
(1, 'PotashCorp Project', 'No', 1, '2017-04-29', '2017-05-31', '<p>We are still working on this project</p>\r\n', '364833_false.pdf', 1, '2017-04-14', '489070_false.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE IF NOT EXISTS `publication` (
  `id` int(11) NOT NULL,
  `name` varchar(600) NOT NULL,
  `category` varchar(500) NOT NULL,
  `date_published` date NOT NULL,
  `description` text NOT NULL,
  `media` varchar(600) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_registered` date NOT NULL,
  `image` varchar(300) NOT NULL,
  `featured` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publication`
--

INSERT INTO `publication` (`id`, `name`, `category`, `date_published`, `description`, `media`, `status`, `date_registered`, `image`, `featured`) VALUES
(1, 'Business Strategies', '3', '2017-04-05', '<p>Please is this published test</p>\r\n', '499489_2017_04_12.pdf', 1, '2017-04-14', '179665_2017_04_05.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `publication_category`
--

CREATE TABLE IF NOT EXISTS `publication_category` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publication_category`
--

INSERT INTO `publication_category` (`id`, `name`, `description`, `image`) VALUES
(1, 'Journal Article', 'IEEE Journal', '743929_journal_article.gif'),
(2, 'Magazine Article', 'IEEE Magazine', '655348_magazine_article.jpeg'),
(3, 'Conference Paper', 'General Conference', '411847_human_resources.jpg'),
(4, 'Book', 'Text book or manual', '116459_book.png'),
(5, 'Submitted for Review', 'Paper submitted already for review', '137949_submitted_for_review.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE IF NOT EXISTS `quote` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(500) NOT NULL,
  `image` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quote`
--

INSERT INTO `quote` (`id`, `content`, `author`, `image`) VALUES
(1, 'Being in control of your life and having realistic expectations about your day-to-day challenges are the keys to stress management, which is perhaps the most important ingredient to living a happy, healthy and rewarding life.', 'Marilu Henner', '291958_1453376785.png'),
(3, 'The biggest risk is not taking any risk... In a world that changing really quickly, the only strategy that is guaranteed to fail is not taking risks.\r\n', 'Mark Zuckerbergs', '290365_1453377011.jpe');

-- --------------------------------------------------------

--
-- Table structure for table `resume`
--

CREATE TABLE IF NOT EXISTS `resume` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `document` varchar(900) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resume`
--

INSERT INTO `resume` (`id`, `name`, `document`) VALUES
(1, 'Latest CV', '958941_latest_cv.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `name` varchar(200) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`name`, `value`) VALUES
('ABOUT_US', '<p><strong>OUR BEGINNING </strong></p>\r\n\r\n<p>Impact Training &amp; Management Consulting Limited was registered and commenced business in 2003, with highly experienced consultants as<br />\r\nits directors.</p>\r\n\r\n<p><strong>OUR OBJECTIVES </strong></p>\r\n\r\n<ul>\r\n	<li>To affect positively our clients business by enhancing the quality<br />\r\n	of their manpower.</li>\r\n	<li>To partner with our clients; working to realise their aspirations.</li>\r\n	<li>To achieve definite and long lasting advantages in the market place.</li>\r\n</ul>\r\n\r\n<p><strong>OUR VALUES </strong></p>\r\n\r\n<ul>\r\n	<li>To act with due diligence in pursuit of excellence for our clients in an environment of mutual respect and trust</li>\r\n	<li>To deliver just-in-time quality learning interventions in the most cost effective way</li>\r\n	<li>To improve worldforce effectiveness at both individual and organisational levels</li>\r\n	<li>To partner with organisations and ensure relevant hands-on-and direct-to-function training.</li>\r\n</ul>\r\n\r\n<p><strong>OUR EXPERIENCE </strong></p>\r\n\r\n<p>Over the decade, we have worked individually and collectively with over three hundred diverse business, spanning all sectors of the Nigerian economy including highly respected multinational companies and indigenous institutions.</p>\r\n\r\n<p><strong>OUR APPROACH </strong></p>\r\n\r\n<p>Our methodologies are competency driven. The required attributes in knowledge, skills and attitudes are designed into our programmes and practically impacted. This way, we relate to clients in different models; as Consultants, Coaches, Advisors, Co-learners and Faciltators in order to infuse conceptual knowledge and ready to use skills.</p>\r\n\r\n<p><strong>OUR LEARNING CENTRE </strong></p>\r\n\r\n<p>We operate in a well equipped learning centre located in an accessible, serene environment in Ilupeju, Lagos.</p>\r\n\r\n<p><strong>OUR PARTNERS </strong></p>\r\n\r\n<p>Our partnership is made up of individuals with pedigree that continues to show high level commitment to insightful consulting and quality training. This is the essence of our profile. We are truly synergistic team with special skills and experience across disciplines and sectors acquired over many years. This is your guarantee of quality service.</p>\r\n\r\n<p>&nbsp;</p>\r\n'),
('ADDTHIS_SHARE_BUTTON', '<!-- Go to www.addthis.com/dashboard to customize your tools -->\r\n<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56a5fbdb49cbb5db" async="async"></script>\r\n'),
('ANALYTICS', '<script></script>'),
('BOOKMARK_BUTTON', '<p>FALSE</p>\r\n'),
('COMPANY_ACC_DETAILS', '<p><strong>Access Bank Plc </strong><strong>Account No: 0034932953 </strong></p>\r\n\r\n<p><strong>Sort Code: 044152273&nbsp;</strong></p>\r\n'),
('COMPANY_ADDRESS', '<span>10, Obokun Street,<br />\r\noff Coker Road, Ilupeju, Lagos State Nigeria.</span>\r\n'),
('COMPANY_ADDRESS_GMAP', '<p>10 Obokun Street</p>\r\n'),
('COMPANY_EMAIL', '<p>vladimir.okhmatovski@umanitoba.ca</p>\n'),
('COMPANY_HOTLINE', '<p>+2348033014321</p>\r\n'),
('COMPANY_NAME', '<p>Impact Training &amp; Management Consulting</p>\r\n'),
('COMPANY_NUMBERS', '<p>+234-1-7932390<br />\r\n+234 803-3876456<br />\r\n+234 802-3060462</p>\r\n'),
('COMPANY_OTHER_EMAILS', '<p>info@impactconsultingng.com</p>\r\n'),
('DRIBBBLE_LINK', '<p>https://dribbble.com/</p>\r\n'),
('FACEBOOK_ADMINS', '<p>0</p>\r\n'),
('FACEBOOK_APP_ID', '<p>0</p>\r\n'),
('FACEBOOK_LINK', '<p>https://www.facebook.com/</p>\r\n'),
('GOOGLEPLUS_LINK', '<p>https://www.plus.google.com/</p>\r\n'),
('HOMEPAGE_CORE_SOLUTION_HEADER', '<p>Core Solution</p>\r\n'),
('HOMEPAGE_CORE_SOLUTION_ICON', '<p>cog</p>\r\n'),
('HOMEPAGE_CORE_SOLUTION_LINK', '<p>about-us/</p>\r\n'),
('HOMEPAGE_CORE_SOLUTION_TEXT', '<p>We provide training essentially in Forensic Accounting and Fraud Examination as well as Banking &amp; Finance, Risk, Management and Supply Chain.</p>\r\n'),
('HOMEPAGE_COURSE_CATEGORIES_HEADER', '<p>Course Categories</p>\r\n'),
('HOMEPAGE_COURSE_CATEGORIES_ICON', '<p>graduation-cap</p>\r\n'),
('HOMEPAGE_COURSE_CATEGORIES_LINK', '<p>courses/</p>\r\n'),
('HOMEPAGE_COURSE_CATEGORIES_TEXT', '<p>View list of our course categories from accounting to supply chain management and book a seat for the ones that meets your professional needs.</p>\r\n'),
('HOMEPAGE_DOWNLOAD_BROCHURE_HEADER', '<p>Download Brochure</p>\r\n'),
('HOMEPAGE_DOWNLOAD_BROCHURE_ICON', '<p>download</p>\r\n'),
('HOMEPAGE_DOWNLOAD_BROCHURE_LINK', '<p>download-brochure</p>\r\n'),
('HOMEPAGE_DOWNLOAD_BROCHURE_TEXT', '<p>Download our comprehensive brochure to view all our courses we offer at your convenience round the year and its free.</p>\r\n'),
('HOMEPAGE_WHO_WE_ARE_HEADER', '<p>Who We Are</p>\r\n'),
('HOMEPAGE_WHO_WE_ARE_ICON', '<p>group</p>\r\n'),
('HOMEPAGE_WHO_WE_ARE_LINK', '<p>about-us/</p>\r\n'),
('HOMEPAGE_WHO_WE_ARE_TEXT', '<p>TSI was founded on the corporate vision of &ldquo;Complete Solution&rdquo; in a global arena with strong bias for research and training.</p>\r\n'),
('LINKEDIN_LINK', '<p>https://www.linkedin.com/</p>\r\n'),
('PINTEREST_LINK', '<p>https://www.pinterest.com/</p>\r\n'),
('SETTINGS_PANEL', '<p>FALSE</p>\r\n'),
('TOTAL_DISPLAYABLE_COURSES', '<p>100</p>\r\n'),
('TWITTER_ID', '<p>0</p>\r\n'),
('TWITTER_LINK', '<p>https://twitter.com/impactconslt</p>\r\n'),
('WELCOME_MESSAGE', '<p style="text-align: justify;">We provide training essentially in Forensic Accounting and Fraud Examination as well as Banking &amp; Finance, Risk, Management and Supply Chain. TSI Limited is a one stop shop for high-quality training, research and consultancy services and customer satisfaction is our greatest priority. We hope you can find all your training needs here.</p>\r\n'),
('YOUTUBE_LINK', '<p>https://www.youtube.com/</p>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `sponsor`
--

CREATE TABLE IF NOT EXISTS `sponsor` (
  `id` int(11) NOT NULL,
  `name` varchar(700) NOT NULL,
  `logo` varchar(700) NOT NULL,
  `website` varchar(700) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_added` date NOT NULL,
  `product` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`id`, `name`, `logo`, `website`, `status`, `date_added`, `product`, `description`, `image`) VALUES
(1, 'First Bank Plc', '642862_first_bank_plc.png', 'https://www.firstbanknigeria.com/', 1, '2016-01-21', 'Personal Banking, Business and E-Banking', '<p><a href="https://www.firstbanknigeria.com/products/e-banking/" style="color: rgb(33, 70, 151); text-decoration: none; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);" title="E-Banking"><img class="alignleft frame preview" src="https://www.firstbanknigeria.com/assets/Uploads/e-bank.jpg" style="border-radius:4px; border:0px solid rgb(255, 255, 255); box-shadow:0px 1px 2px rgba(0, 0, 0, 0.298); display:block; float:right; height:100px; margin:10px 0px; max-width:530px; outline:medium none; position:relative; transition:all 0.2s ease-in 0s; width:130px" /></a></p>\r\n\r\n<div style="display: inline; color: rgb(17, 17, 17); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);">\r\n<h3><a href="https://www.firstbanknigeria.com/products/e-banking/" style="color: rgb(33, 70, 151); text-decoration: none; font-weight: bold;">E-Banking</a></h3>\r\nFirst Bank has a wide range of intuitive services that make your banking easy, anytime, anywhere. You can now access banking services on the web, on your phone, via SMS and/or by telephoning in.</div>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<div style="display: inline; color: rgb(17, 17, 17); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);">\r\n<h3><a href="https://www.firstbanknigeria.com/products/business/" style="color: rgb(33, 70, 151); text-decoration: none; font-weight: bold;">Business</a><a href="https://www.firstbanknigeria.com/products/business/" style="color: rgb(72, 47, 128); text-decoration: none; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);" title="Business"><img class="alignleft frame preview" src="https://www.firstbanknigeria.com/assets/Uploads/business-fbn.jpg" style="border-radius:4px; border:0px solid rgb(255, 255, 255); box-shadow:0px 1px 2px rgba(0, 0, 0, 0.298); display:block; float:right; height:65px; margin:10px 0px; max-width:530px; outline:medium none; position:relative; transition:all 0.2s ease-in 0s; width:130px" /></a></h3>\r\nOur business range of products enables businesses to get the best out of their accounts. Reduced Interest rates, access to loans, support services and more from First Bank.</div>\r\n\r\n<p>&nbsp;</p>\r\n', '478204_first_bank_plc.png'),
(2, 'Nigerian Breweries Plc', '347714_nigerian_breweries_plc.png', 'http://nbplc.com/', 1, '2016-01-21', 'Drinks', '<p>In 1957, the company commissioned its second brewery in Aba. <span style="color:#003299">Kaduna Brewery</span> was commissioned in 1963 while <span style="color:#003299">Ibadan Brewery</span> came on stream in 1982. In 1993, the company acquired its fifth brewery in Enugu. In October 2003, a sixth brewery, sited at Ameke, in Enugu State was commissioned and christened <span style="color:#003299">Ama Brewery</span>. Ama Brewery is today, the biggest and most modern brewery in Nigeria.</p>\r\n\r\n<p>Operations in the Old Enugu Brewery were however discontinued in 2004, while the company acquired a malting Plant in Aba in 2008.</p>\r\n\r\n<p>--&gt;</p>\r\n\r\n<p>We are proudly Nigeria&rsquo;s pioneer and largest Brewing firm. Our company was incorporated in 1946 and in June 1949, we recorded a landmark when the first bottle of STAR lager beer rolled off our <span style="color:#003299">Lagos Brewery</span> bottling lines. This first brewery in Lagos has undergone several optimization processes and as at today boasts of one of the most modern brew house in the country.</p>\r\n\r\n<p>In 1957, we commissioned our second brewery in Aba. The <span style="color:#003299">Aba Brewery</span> has also recently undergone several optimization processes and has been fitted with best in brewery technology. In 1963 we commissioned our <span style="color:#003299">Kaduna Brewery</span> while Ibadan Brewery came on stream in 1982. In 1993, we acquired our fifth brewery in Enugu. A sixth brewery, sited at Ama-eke in 9th Mile, Enugu was commissioned and christened <span style="color:#003299">Ama Brewery</span> in October 2003. Ama Brewery is today the biggest and most modern brewery in Nigeria.</p>\r\n\r\n<p>Operations in the Old Enugu Brewery were however discontinued in 2004. We acquired a malting Plant in Aba in 2008.</p>\r\n\r\n<p>In October 2011, our company bought majority equity interests in Sona Systems Associates Business Management Limited, (Sona Systems) and Life Breweries company Limited from Heineken N.V. This followed Heineken&rsquo;s acquisition of controlling interests in five breweries in Nigeria from Sona Group in January 2011. Sona Systems&rsquo; two breweries in Ota and Kaduna, and Life Breweries in Onitsha have now become part of Nigerian Breweries Plc, together with the three brands: Goldberg lager, Malta Gold and Life Continental lager.</p>\r\n\r\n<p><!--The merger became final on December 31, 2014.-->In 2014, we got approval from the Securities and Exchange Commission and the respective shareholders of both Nigerian Breweries Plc and Consolidated Breweries Plc to merge the operations of both companies. The merger became final on December 31, 2014</p>\r\n\r\n<p>Following the successful merger, we now have three additional breweries in Ijebu-Ode, Ogun State, Awo-Omamma in Imo State and Makurdi in Benue State. The merger also brought an additional seven brands into our portfolio.</p>\r\n\r\n<p>Thus, from that humble beginning in 1946, our company has now grown into a Brewing Company with 11 breweries, 2 malting plants and 26 Sales depots from which our high quality products are distributed to all parts of Nigeria.</p>\r\n\r\n<p>Nigerian Breweries Plc has a growing export business which covers global sales and marketing of our brands and dates back to 1986. NB Plc offers sales, logistics and marketing support to make our brands shelf-ready in international markets, including world-class outlets such as TESCO and ASDA Stores in the United Kingdom. Our brands are available in over thirteen countries, across the United Kingdom, South Africa, Middle-East, West Africa and the United States of America.</p>\r\n', '397729_nigerian_breweries_plc.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `email` varchar(200) NOT NULL,
  `company` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `picture` text NOT NULL,
  `website` varchar(300) NOT NULL,
  `skype_id` varchar(200) NOT NULL,
  `yahoo_id` varchar(200) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `username` varchar(300) NOT NULL,
  `password` varchar(500) NOT NULL,
  `time_entered` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `facebook_id` varchar(300) NOT NULL,
  `twitter_id` varchar(400) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `company`, `country`, `description`, `picture`, `website`, `skype_id`, `yahoo_id`, `phone`, `address`, `username`, `password`, `time_entered`, `status`, `facebook_id`, `twitter_id`) VALUES
(1, 'Kaiste Ventures Limited', 'info@kaisteventures.com', '', '', '', '', '', '', '', '', '', '', '', '1453378931', 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `video` varchar(300) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `name`, `description`, `video`) VALUES
(1, 'Human Resources via Monash University', 'Human resources managers', '998078_competitive_strategies_ii.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `webpage`
--

CREATE TABLE IF NOT EXISTS `webpage` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(700) NOT NULL,
  `keywords` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `webpage`
--

INSERT INTO `webpage` (`id`, `name`, `title`, `description`, `keywords`) VALUES
(1, 'home', 'Home', 'We are a consulting and training firm / provider in Nigeria. We offer open programmes, bespoke and implant management training courses in Nigeria.', 'group, home'),
(2, 'contact', 'Contact Us', 'Contact us', 'contact, enquiries'),
(3, 'course-detail', 'Course Details', 'Course description', 'course, detail'),
(4, 'category-detail', 'Category Details', 'Category description', 'category, detail'),
(5, 'event-detail', 'Event Details', 'Event description', 'event, detail'),
(6, 'course-categories', 'Course Categories', 'Course categories', 'course, category'),
(7, 'about', 'About Us', 'About Us', 'about, impact, consulting, management'),
(8, 'gallery', 'Our training gallery', 'training gallery - photos and images', 'gallery, photo, image'),
(9, '404', '404 - Page Not Found', 'The page you are looking for cannot be found or has been removed.', '404, found, not, page, remove'),
(10, '403', 'Forbidden Access', 'Access Denied. You are not allowed to access the content of this page.', 'forbidden, 403, access, denied'),
(11, 'members', 'Team Members', 'Team members', 'team, member'),
(12, 'member-detail', 'Member Details', 'Member information or details', 'member, detail, info'),
(13, 'clients', 'Our Clients and Partners', 'Our clients and sponsors .', 'partner, link, useful'),
(14, 'events', 'All Upcoming Events', 'All upcoming events. ', 'event, all, upcoming'),
(15, 'search', 'Search Results', 'Search results', 'search, result'),
(16, 'faqs', 'Frequently Asked Questions', 'Frequently asked questions', 'faq, question, answer'),
(17, 'courses', 'All Courses', 'All available training courses and categories in Nigeria and around the World', 'course, training'),
(19, 'course', 'Course Detail', 'Course Detail Description', 'course'),
(20, 'member', 'Member Info', 'Member information or details', 'member, detail, info'),
(21, 'event', 'All Events', 'All upcoming events. ', 'event, all, upcoming'),
(22, 'videos', 'All Videos', 'All trainings videos and seminar videos', 'training, seminar, video');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question` (`question`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `patent`
--
ALTER TABLE `patent`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `presentation`
--
ALTER TABLE `presentation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evt_st_dat_end_dat` (`name`,`date_presented`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evt_st_dat_end_dat` (`name`,`start_date`,`end_date`);

--
-- Indexes for table `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evt_st_dat_end_dat` (`name`,`date_published`);

--
-- Indexes for table `publication_category`
--
ALTER TABLE `publication_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resume`
--
ALTER TABLE `resume`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `sponsor`
--
ALTER TABLE `sponsor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`logo`),
  ADD UNIQUE KEY `website` (`website`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `webpage`
--
ALTER TABLE `webpage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `title` (`title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `patent`
--
ALTER TABLE `patent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `presentation`
--
ALTER TABLE `presentation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `publication`
--
ALTER TABLE `publication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `publication_category`
--
ALTER TABLE `publication_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `resume`
--
ALTER TABLE `resume`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sponsor`
--
ALTER TABLE `sponsor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `webpage`
--
ALTER TABLE `webpage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
