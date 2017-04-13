-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2016 at 09:12 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `impact`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` varchar(100) NOT NULL,
  `date_registered` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `username`, `password`, `role`, `date_registered`) VALUES
(1, 'Mojolagbe Jamiu Babatunde', 'mojolagbe@gmail.com', 'Babatunde', 'ae2b1fca515949e5d54fb22b8ed95575', 'Sub-Admin', '2015-08-20'),
(2, 'Administrator', 'info@impactconsultingng.com', 'Admin', 'ae2b1fca515949e5d54fb22b8ed95575', 'Admin', '2015-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(600) NOT NULL,
  `short_name` varchar(200) NOT NULL,
  `category` varchar(500) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `media` varchar(600) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_registered` date NOT NULL,
  `image` varchar(300) NOT NULL,
  `featured` tinyint(4) NOT NULL,
  `currency` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `evt_st_dat_end_dat` (`name`,`start_date`,`end_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `name`, `short_name`, `category`, `start_date`, `end_date`, `code`, `description`, `media`, `amount`, `status`, `date_registered`, `image`, `featured`, `currency`) VALUES
(1, 'Analysing Financial Statement', 'Analysing Financial Statement', '1', '2016-04-19', '2016-04-21', '', '<p>Our Financial Analysis course takes you through the &ldquo;step-by-step&rdquo; of financial statements interpretation.</p>\r\n\r\n<p>Participants will gain clear insight into the techniques that investors, creditors, bankers and other analysts use to evaluate organisations. Using basic excel, participants will learn how to evaluate current operations as well as anticipate future corporate performance. Everyone on this programme will leave better equipped to make decisions that can positively impact the fortune of their organisation</p>\r\n\r\n<p><strong>LEARNING OBJECTIVES:</strong></p>\r\n\r\n<p>Participants will:</p>\r\n\r\n<p>&bull; Possess deeper understanding of the relationships among the three major financial statements</p>\r\n\r\n<p>&bull; Understand how business decisions affect interrelationship among items of the statement</p>\r\n\r\n<p>&bull; Identify business operations which drives movements in financial statements</p>\r\n\r\n<p>&bull; Apply financial ratios to analyze trends, competitors, and future economic decisions</p>\r\n\r\n<p>&bull; Utilize spread sheet tols and techniques in analyzing financial statements</p>\r\n\r\n<p><strong>LEARNING CONTENTS:</strong></p>\r\n\r\n<p>&bull; The Financial statements</p>\r\n\r\n<p>&bull; Profit and cash flow statements</p>\r\n\r\n<p>&bull; Ratio analysis</p>\r\n\r\n<p>&bull; Interpretation of accounts</p>\r\n\r\n<p>&bull; Addressing potential problems revealed in financial statements</p>\r\n\r\n<p>&bull; Benchmarking business performance</p>\r\n\r\n<p>&bull; Financial information databases</p>\r\n\r\n<p>&bull; Budgets and forecasts</p>\r\n\r\n<p>&bull; The lenders perspectives of financial statements</p>\r\n\r\n<p>&bull; Applying spreadsheets in comparisons of divisions, business units and entire companies</p>\r\n', '286328_.pdf', '88500', 1, '2016-01-21', '400229_analysing_financial_statement.jpg', 1, 'NGN'),
(2, 'Banking Operations Course', 'Banking Operations Course', '1', '2016-04-04', '2016-04-06', '', '<p>Understanding banking operations is a key requirement for all entry level staff of financial institutions.</p>\r\n\r\n<p>This course on basic banking operations has been designed to provide participants with the knowledge and skills required for effectively processing banking transactions. They will also learn the control measures in order to eliminate losses.</p>\r\n\r\n<p><strong>LEARNING OBJECTIVES:</strong></p>\r\n\r\n<p>Participants will:</p>\r\n\r\n<p>&bull; Understand the intermediation roles of banks and other financial institutions</p>\r\n\r\n<p>&bull; Understand banking operations terminologies to build self confidence and professionalism</p>\r\n\r\n<p>&bull; Process banking transactions without errors and losses</p>\r\n\r\n<p>&bull; Understand the various banking products - their features and benefits to customers</p>\r\n\r\n<p>&bull; Understand banking operations control systems</p>\r\n\r\n<p>&bull; Appreciate their roles in working with other departments of the organisation for effective customer service delivery</p>\r\n\r\n<p><strong>LEARNING CONTENTS:</strong></p>\r\n\r\n<p>&bull; The business of banking</p>\r\n\r\n<p>&bull; Accounts opening functions</p>\r\n\r\n<p>&bull; Basic accounting for cash operations</p>\r\n\r\n<p>&bull; Cash and teller operations</p>\r\n\r\n<p>&bull; Money market products and processing</p>\r\n\r\n<p>&bull; CBN clearing and clearing regulations</p>\r\n\r\n<p>&bull; Income and expenses processing</p>\r\n\r\n<p>&bull; Government revenue collection services</p>\r\n\r\n<p>&bull; Introduction to international trade; Bills for collection, and letters of credit</p>\r\n\r\n<p>&bull; Computer operations in banking services</p>\r\n\r\n<p>&bull; Anti money laundering and know your customer</p>\r\n', '', '88500', 1, '2016-01-21', '650525_.jpg', 0, 'NGN'),
(3, 'Management and Business Skills for Secretaries and Executive Assistants', 'Management and Business Skills', '2', '2016-04-05', '2016-04-07', '', '<p>The changing nature of business and pressure for results mean more responsibilities for the secretary in addition to the traditional roles. This course is designed to prepare the secretary and executive assistant to meet the expectation and dynamics of the modern workplace.</p>\r\n\r\n<h4>LEARNING OBJECTIVES</h4>\r\n\r\n<p>Participants will:</p>\r\n\r\n<p>Run the office with confidence and competence</p>\r\n\r\n<p>Acquire skills of personal effectiveness</p>\r\n\r\n<p>Handle confidentiality with greater care</p>\r\n\r\n<p>Key into their bosses aspirations</p>\r\n\r\n<p>Become more proactive</p>\r\n\r\n<p>LEARNING CONTENTS</p>\r\n\r\n<ul>\r\n	<li>Emerging issues in business/business drivers</li>\r\n	<li>Transiting from secretarial to managerial role</li>\r\n	<li>Duties and responsibilities of secretaries and executive assistants</li>\r\n	<li>Role of secretaries and PA&rsquo;s in meetings, conferences and functions</li>\r\n	<li>Management principles</li>\r\n	<li>Situational leadership</li>\r\n	<li>Interpersonal skills and dealing with difficult people</li>\r\n	<li>Habits of personal effectiveness/assertiveness</li>\r\n	<li>Principles of effective communication</li>\r\n	<li>Priority and stress management</li>\r\n	<li>Managing sensitive information and documents</li>\r\n	<li>Working with more than one manager</li>\r\n	<li>Customer service principles</li>\r\n	<li>Transactional analysis</li>\r\n	<li>Career outlook for secretaries</li>\r\n	<li>Basic book keeping and accounts</li>\r\n	<li>Essentials of business law</li>\r\n</ul>\r\n', '', '88500', 1, '2016-01-21', '887116_management_and_business_skills.jpg', 0, 'NGN'),
(4, 'Office Management and Effective Administration Skills ', 'Office Management and Effective Administration Skills ', '2', '2016-02-16', '2016-02-18', '', '<p style="text-align: justify;">The office administrator&rsquo;s job has become more complex, challenging and stretched to encom&shy;pass multiple functions beyond the normal job description. This course is designed to fill the skills gap experienced by most administrative assistants and office managers. Participants will leave the Course equipped with skills to improve their performance and productivity.</p>\r\n\r\n<p style="text-align: justify;">LEARNING OBJECTIVES</p>\r\n\r\n<p style="text-align: justify;">Participants will learn :</p>\r\n\r\n<ul>\r\n	<li style="text-align: justify;">How to handle multiple projects and assignments</li>\r\n	<li style="text-align: justify;">The techniques of getting the best out of people including the boss</li>\r\n	<li style="text-align: justify;">Practical techniques of getting jobs done in less time</li>\r\n	<li style="text-align: justify;">How to manage crises and difficult situations</li>\r\n	<li style="text-align: justify;">Skills of managing a variety of functions in a modern office setting</li>\r\n</ul>\r\n\r\n<p style="text-align: justify;">LEARNING CONTENTS</p>\r\n\r\n<ul>\r\n	<li style="text-align: justify;">Challenges and opportunities for the office manager</li>\r\n	<li style="text-align: justify;">Planning, organising, directing and controlling</li>\r\n	<li style="text-align: justify;">Staying in sync with your boss</li>\r\n	<li style="text-align: justify;">Using 80/20 rule to identify important tasks</li>\r\n	<li style="text-align: justify;">Tools of creative thinking</li>\r\n	<li style="text-align: justify;">Facility management</li>\r\n	<li style="text-align: justify;">Fleet management</li>\r\n	<li style="text-align: justify;">Travel management</li>\r\n	<li style="text-align: justify;">Crises management techniques</li>\r\n	<li style="text-align: justify;">How to influence people</li>\r\n	<li style="text-align: justify;">Personal effectiveness</li>\r\n	<li style="text-align: justify;">Principles of negotiation</li>\r\n	<li style="text-align: justify;">Writing readable reports</li>\r\n	<li style="text-align: justify;">Managing the office</li>\r\n	<li style="text-align: justify;">Confidentiality and access</li>\r\n</ul>\r\n', '', '88500', 1, '2016-01-21', '668598_office_management_and_effective_administration_skills_.jpg', 1, 'NGN'),
(5, 'Fundamentals of Human Resources Management', 'Fundamentals of HR Management', '3', '2016-06-20', '2016-06-22', '', '<p>This programme has been designed to prepare people new in the HR function to make a smooth transi&shy;tion into their roles. Participants will be grounded in the basic HR activities of attracting, engaging, de&shy;veloping and managing employee expectations to enable them contribute their best to the organization.</p>\r\n\r\n<p>Participants will also learn the administration support functions in HR setting.</p>\r\n\r\n<p>LEARNING OBJECTIVES</p>\r\n\r\n<p>Participants will:</p>\r\n\r\n<ul>\r\n	<li>Understand the roles and responsibilities of HR function to the business</li>\r\n	<li>Help operational staff in meeting their needs.</li>\r\n	<li>Assist in collating HR metrics</li>\r\n	<li>Understand the laws relating to contract of employment</li>\r\n</ul>\r\n\r\n<p>LEARNING CONTENTS</p>\r\n\r\n<ul>\r\n	<li>Importance and definition of human resource</li>\r\n	<li>HR function: Past, Present, future</li>\r\n	<li>HR department roles &amp; responsibilities</li>\r\n	<li>HR value proposition</li>\r\n	<li>Employee life cycle</li>\r\n	<li>Manpower planning</li>\r\n	<li>Recruitment and selection</li>\r\n	<li>Training and development</li>\r\n	<li>Target setting and performance appraisal</li>\r\n	<li>Benefits and compensation</li>\r\n	<li>Employee engagement</li>\r\n	<li>Laws of contract of employment</li>\r\n	<li>Grievance handling, counseling, and disciplinary procedures.</li>\r\n	<li>Introduction to industrial relations</li>\r\n	<li>Personnel administration</li>\r\n	<li>Staff handbook as HR tool</li>\r\n	<li>HR metrics collation</li>\r\n	<li>Employee satisfaction survey</li>\r\n</ul>\r\n', '', '88500', 1, '2016-01-21', '435605_fundamentals_of_hr_management.jpe', 0, 'NGN'),
(6, 'Achieving Targets  Through Performance  Dialogue', 'Achieving Targets  Through Performance  Dialogue', '3', '2016-03-21', '2016-03-22', '', '<p>Performance dialogue is the series of conversation between managers and employees to plan, manage and review performance all year long. Achieving results through others depends on skilled interventions of managers and it is also through these conversations that managers can build trust, openness and col&shy;laboration.</p>\r\n\r\n<p>This workshop puts performance discussions and reviews into context, and offers a step-by-step pro&shy;cess for preparing and handling the conversation itself.</p>\r\n\r\n<p><u>LEARNING OBJECTIVES </u></p>\r\n\r\n<p style="margin-left:8.0pt">Participants will:</p>\r\n\r\n<ul>\r\n	<li>Use performance reviews and regular one-to-one dialogue in achieving team and organisational goals</li>\r\n	<li>Set and agree SMART goals with team members</li>\r\n	<li>Measure regularly against agreed standards</li>\r\n	<li>Prepare professionally for the review sessions</li>\r\n	<li>Use critical techniques and skills for managing the conversation</li>\r\n	<li>including handling disagreements</li>\r\n	<li>Give performance feedback more effectively</li>\r\n	<li>Motivate every team member to higher productivity</li>\r\n</ul>\r\n\r\n<p><u>LEARNING CONTENTS </u></p>\r\n\r\n<ul>\r\n	<li>The Performance Dialogue Cycle</li>\r\n	<li>Defining performance dialogue and its characteristics</li>\r\n	<li>Approach to goal Setting</li>\r\n	<li>Evaluating and addressing performance</li>\r\n	<li>Understanding performance: The Onion Model</li>\r\n	<li>Handling difficult conversations / constructive feedback</li>\r\n	<li>Motivating performance</li>\r\n	<li>Conducting effective appraisal</li>\r\n</ul>\r\n', '', '88500', 1, '2016-01-21', '857013_achieving_targets__through_performance__dialogue.png', 1, 'NGN'),
(8, 'Superior Customer Service', 'Superior Customer Service', '4', '2016-01-27', '2016-01-29', '', '<p>In today&rsquo;s sophisticated market, the quality of product is not on its own sufficient to maintain or expand market share. Improving customer relations is one enduring way of keeping your customers. Customers are individuals; they need to feel that you care. Every form of customer contact, if well managed, provides an opportunity to build a relationship and develop future business. This course will help you develop the skills to enable you become customer focused and deliver excellent service to your customers.</p>\r\n\r\n<p><strong>LEARNING OBJECTIVES</strong></p>\r\n\r\n<p>Participants will</p>\r\n\r\n<p>&bull; Understand the nature of service</p>\r\n\r\n<p>&bull; Work as a service team</p>\r\n\r\n<p>&bull; Learn empathic listening skills</p>\r\n\r\n<p>&bull; Display excellent attitude to customers</p>\r\n\r\n<p>&bull; Respond appropriately to different customer personality types</p>\r\n\r\n<p>&bull; Execute service recovery efforts</p>\r\n\r\n<p>&bull; Manage difficult customers</p>\r\n\r\n<p><strong>LEARNING CONTENTS</strong></p>\r\n\r\n<p>&bull; Definition and characteristics of service</p>\r\n\r\n<p>&bull; Desirable behaviours in customer service</p>\r\n\r\n<p>&bull; The six rules of customer contact</p>\r\n\r\n<p>&bull; Moments of truth concept</p>\r\n\r\n<p>&bull; Building customer loyalty and retention</p>\r\n\r\n<p>&bull; Communication skills</p>\r\n\r\n<p>&bull; How to meet the needs of different types of customers</p>\r\n\r\n<p>&bull; Keeping service promise</p>\r\n\r\n<p>&bull; Empathetic listening skills</p>\r\n\r\n<p>&bull; What to do when there are problems</p>\r\n\r\n<p>&bull; Handling difficult customers</p>\r\n\r\n<p>&bull; Promoting team spirit (external - internal customer service concept)</p>\r\n\r\n<p>&bull; The total service concept</p>\r\n\r\n<p>&bull; Customer life value (CLV)</p>\r\n\r\n<p>&bull; Finding positive solutions to customer complaints</p>\r\n\r\n<p>&bull; Continuous service improvement process</p>\r\n\r\n<p>&bull; Customer experience mapping</p>\r\n', '', '88500', 1, '2016-01-21', '752725_.jpe', 1, 'NGN'),
(9, 'Debt Recovery For Financial Institutions', 'Debt Recovery For Financial Institutions', '5', '2016-02-16', '2016-02-18', '', '<p>Timely collection of debts is critical to successful business operations. Ability to recover debts, however, starts from the manner in which the credit is written. Provision of a good credit policy and understand&shy;ing of trade operation practices form part of proactive debt recovery process. This course will teach techniques that will help streamline your organisation&rsquo;s practices, improve risk management and max&shy;imise the effectiveness of debt collection process. Senior bankers and accountants will share personal experiences and knowledge on this programme.</p>\r\n\r\n<p><u>LEARNING OBJECTIVES: </u></p>\r\n\r\n<p>Participants will learn:</p>\r\n\r\n<ul>\r\n	<li>How to prepare competent credit evaluation</li>\r\n	<li>How to identify troubled loans</li>\r\n	<li>The use of credit risk management tools</li>\r\n	<li>The basics of managing turnarounds</li>\r\n	<li>How to construct loan workouts</li>\r\n	<li>The legal procedures in loan recovery</li>\r\n	<li>Manage difficult customers and accounts</li>\r\n</ul>\r\n\r\n<p><u>LEARNING CONTENTS: </u></p>\r\n\r\n<ul>\r\n	<li>Credit Assessment &amp; Risk evaluation</li>\r\n	<li>Financial analysis in credit evaluation</li>\r\n	<li>Factors Influencing credit decisions and the pitfalls involved</li>\r\n	<li>Collection system and procedures</li>\r\n	<li>Collection tools and techniques</li>\r\n	<li>Debt recovery: key success factors</li>\r\n	<li>Dealing with delay tactics</li>\r\n	<li>Dealing with distressed debtors</li>\r\n	<li>Legal Perspective in debt collection</li>\r\n	<li>Negotiating for a settlement</li>\r\n</ul>\r\n', '', '88500', 1, '2016-01-21', '271991_debt_recovery_for_financial_institutions.png', 0, 'NGN');

-- --------------------------------------------------------

--
-- Table structure for table `course_brochure`
--

CREATE TABLE IF NOT EXISTS `course_brochure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `document` varchar(900) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `course_brochure`
--

INSERT INTO `course_brochure` (`id`, `name`, `document`) VALUES
(1, '2015 Open Programme Guide', '382596_2015_open_programme_guide.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `course_category`
--

CREATE TABLE IF NOT EXISTS `course_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `course_category`
--

INSERT INTO `course_category` (`id`, `name`, `description`, `image`) VALUES
(1, 'Business Strategy', 'Business strategy category', '331097_business_strategy.jpg'),
(2, 'General Management', 'General management category', '554120_general_management.jpg'),
(3, 'Human Resources', 'Human resources category', '803757_human_resources.jpg'),
(4, 'Quality and Customer Service', 'Quality and customer service category', '662610_quality_and_customer_service.jpg'),
(5, 'Banking and Finance', 'Banking and finance category', '957291_banking_and_finance.jpe');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(300) NOT NULL,
  `image` varchar(300) NOT NULL,
  `date_time` varchar(300) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `name`, `description`, `location`, `image`, `date_time`, `status`, `date_added`) VALUES
(1, 'Website Launch', '<p><span style="color:rgb(92, 101, 102); font-family:open sans; font-size:14px">The website was redesigned by <a href="http://kaisteventures.com">Kaiste Ventures Limited.</a></span></p>\r\n', 'Ketu, Lagos, Nigeria', '574060_website_launch.jpg', '2016/03/25 20:00', 1, '2015-11-13 13:13:25');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(700) NOT NULL,
  `answer` text NOT NULL,
  `date_added` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question` (`question`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`, `date_added`) VALUES
(1, 'What happens if I am unable to attend a course and I have already paid?', 'Your payment will be withhold until you attend a course of the same amount.', '2016-01-20');

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE IF NOT EXISTS `quote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `author` varchar(500) NOT NULL,
  `image` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `quote`
--

INSERT INTO `quote` (`id`, `content`, `author`, `image`) VALUES
(1, 'Being in control of your life and having realistic expectations about your day-to-day challenges are the keys to stress management, which is perhaps the most important ingredient to living a happy, healthy and rewarding life.', 'Marilu Henner', '291958_1453376785.png'),
(2, 'Management is doing things right; leadership is doing the right things.', 'Peter Drucker', '592242_1453376860.jpg'),
(3, 'The biggest risk is not taking any risk... In a world that changing really quickly, the only strategy that is guaranteed to fail is not taking risks.\r\n', 'Mark Zuckerberg', '290365_1453377011.jpe');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `name` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
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
('COMPANY_EMAIL', '<p>info@impactconsultingng.com</p>\r\n'),
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(700) NOT NULL,
  `logo` varchar(700) NOT NULL,
  `website` varchar(700) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `date_added` date NOT NULL,
  `product` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`logo`),
  UNIQUE KEY `website` (`website`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sponsor`
--

INSERT INTO `sponsor` (`id`, `name`, `logo`, `website`, `status`, `date_added`, `product`, `description`, `image`) VALUES
(1, 'First Bank Plc', '642862_first_bank_plc.png', 'https://www.firstbanknigeria.com/', 1, '2016-01-21', 'Personal Banking, Business and E-Banking', '<p><a href="https://www.firstbanknigeria.com/products/e-banking/" style="color: rgb(33, 70, 151); text-decoration: none; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);" title="E-Banking"><img class="alignleft frame preview" src="https://www.firstbanknigeria.com/assets/Uploads/e-bank.jpg" style="border-radius:4px; border:0px solid rgb(255, 255, 255); box-shadow:0px 1px 2px rgba(0, 0, 0, 0.298); display:block; float:right; height:100px; margin:10px 0px; max-width:530px; outline:medium none; position:relative; transition:all 0.2s ease-in 0s; width:130px" /></a></p>\r\n\r\n<div style="display: inline; color: rgb(17, 17, 17); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);">\r\n<h3><a href="https://www.firstbanknigeria.com/products/e-banking/" style="color: rgb(33, 70, 151); text-decoration: none; font-weight: bold;">E-Banking</a></h3>\r\nFirst Bank has a wide range of intuitive services that make your banking easy, anytime, anywhere. You can now access banking services on the web, on your phone, via SMS and/or by telephoning in.</div>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<div style="display: inline; color: rgb(17, 17, 17); font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);">\r\n<h3><a href="https://www.firstbanknigeria.com/products/business/" style="color: rgb(33, 70, 151); text-decoration: none; font-weight: bold;">Business</a><a href="https://www.firstbanknigeria.com/products/business/" style="color: rgb(72, 47, 128); text-decoration: none; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-style: normal; font-variant: normal; letter-spacing: normal; line-height: 16.8px; orphans: auto; text-align: justify; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);" title="Business"><img class="alignleft frame preview" src="https://www.firstbanknigeria.com/assets/Uploads/business-fbn.jpg" style="border-radius:4px; border:0px solid rgb(255, 255, 255); box-shadow:0px 1px 2px rgba(0, 0, 0, 0.298); display:block; float:right; height:65px; margin:10px 0px; max-width:530px; outline:medium none; position:relative; transition:all 0.2s ease-in 0s; width:130px" /></a></h3>\r\nOur business range of products enables businesses to get the best out of their accounts. Reduced Interest rates, access to loans, support services and more from First Bank.</div>\r\n\r\n<p>&nbsp;</p>\r\n', '478204_first_bank_plc.png'),
(2, 'Nigerian Breweries Plc', '347714_nigerian_breweries_plc.png', 'http://nbplc.com/', 1, '2016-01-21', 'Drinks', '<p>In 1957, the company commissioned its second brewery in Aba. <span style="color:#003299">Kaduna Brewery</span> was commissioned in 1963 while <span style="color:#003299">Ibadan Brewery</span> came on stream in 1982. In 1993, the company acquired its fifth brewery in Enugu. In October 2003, a sixth brewery, sited at Ameke, in Enugu State was commissioned and christened <span style="color:#003299">Ama Brewery</span>. Ama Brewery is today, the biggest and most modern brewery in Nigeria.</p>\r\n\r\n<p>Operations in the Old Enugu Brewery were however discontinued in 2004, while the company acquired a malting Plant in Aba in 2008.</p>\r\n\r\n<p>--&gt;</p>\r\n\r\n<p>We are proudly Nigeria&rsquo;s pioneer and largest Brewing firm. Our company was incorporated in 1946 and in June 1949, we recorded a landmark when the first bottle of STAR lager beer rolled off our <span style="color:#003299">Lagos Brewery</span> bottling lines. This first brewery in Lagos has undergone several optimization processes and as at today boasts of one of the most modern brew house in the country.</p>\r\n\r\n<p>In 1957, we commissioned our second brewery in Aba. The <span style="color:#003299">Aba Brewery</span> has also recently undergone several optimization processes and has been fitted with best in brewery technology. In 1963 we commissioned our <span style="color:#003299">Kaduna Brewery</span> while Ibadan Brewery came on stream in 1982. In 1993, we acquired our fifth brewery in Enugu. A sixth brewery, sited at Ama-eke in 9th Mile, Enugu was commissioned and christened <span style="color:#003299">Ama Brewery</span> in October 2003. Ama Brewery is today the biggest and most modern brewery in Nigeria.</p>\r\n\r\n<p>Operations in the Old Enugu Brewery were however discontinued in 2004. We acquired a malting Plant in Aba in 2008.</p>\r\n\r\n<p>In October 2011, our company bought majority equity interests in Sona Systems Associates Business Management Limited, (Sona Systems) and Life Breweries company Limited from Heineken N.V. This followed Heineken&rsquo;s acquisition of controlling interests in five breweries in Nigeria from Sona Group in January 2011. Sona Systems&rsquo; two breweries in Ota and Kaduna, and Life Breweries in Onitsha have now become part of Nigerian Breweries Plc, together with the three brands: Goldberg lager, Malta Gold and Life Continental lager.</p>\r\n\r\n<p><!--The merger became final on December 31, 2014.-->In 2014, we got approval from the Securities and Exchange Commission and the respective shareholders of both Nigerian Breweries Plc and Consolidated Breweries Plc to merge the operations of both companies. The merger became final on December 31, 2014</p>\r\n\r\n<p>Following the successful merger, we now have three additional breweries in Ijebu-Ode, Ogun State, Awo-Omamma in Imo State and Makurdi in Benue State. The merger also brought an additional seven brands into our portfolio.</p>\r\n\r\n<p>Thus, from that humble beginning in 1946, our company has now grown into a Brewing Company with 11 breweries, 2 malting plants and 26 Sales depots from which our high quality products are distributed to all parts of Nigeria.</p>\r\n\r\n<p>Nigerian Breweries Plc has a growing export business which covers global sales and marketing of our brands and dates back to 1986. NB Plc offers sales, logistics and marketing support to make our brands shelf-ready in international markets, including world-class outlets such as TESCO and ASDA Stores in the United Kingdom. Our brands are available in over thirteen countries, across the United Kingdom, South Africa, Middle-East, West Africa and the United States of America.</p>\r\n', '397729_nigerian_breweries_plc.png');

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE IF NOT EXISTS `tutor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `qualification` text NOT NULL,
  `field` text NOT NULL,
  `bio` text NOT NULL,
  `email` varchar(300) NOT NULL,
  `website` varchar(500) NOT NULL,
  `picture` varchar(300) NOT NULL,
  `visible` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`id`, `name`, `qualification`, `field`, `bio`, `email`, `website`, `picture`, `visible`) VALUES
(1, 'Godwin Onokpachere ', 'B.Sc., M.Sc., LL.B, B.L., ACIB, ACIPM,CEO, HRM', 'General Management ', '<p>Godwin Onokpachere&#39;s biography</p>\r\n', 'godwino@impactconsultingng.com', 'http://www.impactconsultingng.com', '319973_godwin_onokpachere_.jpg', 1),
(2, 'Taiwo Adegun', 'B.Sc., M.Sc., ED', 'Strategy & Quality ', '<p>Taiwo Adegun&#39;s biography</p>\r\n', 'taiwoa@impactconsultingng.com', 'http://www.impactconsultingng.com', '552142_taiwo_adegun.jpg', 1),
(3, ' Funso Oyeniyi', 'B.A, M.Sc., DD, rpa, ED,', ' Sales & Marketing ', '<p>Funso Oyeniyi &#39;s biography</p>\r\n', 'funsoo@impactconsultingng.com', 'http://www.impactconsultingng.com', '700705__funso_oyeniyi.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `twitter_id` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `video` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `name`, `description`, `video`) VALUES
(1, 'Human Resources via Monash University', 'Human resources manager', '998078_competitive_strategies_ii.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `webpage`
--

CREATE TABLE IF NOT EXISTS `webpage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(700) NOT NULL,
  `keywords` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
