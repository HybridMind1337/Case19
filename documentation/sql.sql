-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 26, 2020 at 04:29 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE `aboutus` (
  `aboutus` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aboutus`
--

INSERT INTO `aboutus` (`aboutus`) VALUES
('<strong>Lorem Ipsum</strong><span style=\"color:rgb(0, 0, 0); font-family:arial,helvetica,sans; \r\nfont-size:11px\">\r\n&nbsp;е елементарен примерен текст, \r\nизползван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около \r\n1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да \r\nнапечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, \r\nно е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. \r\nПопуляризиран е през 60те години на 20ти век със издаването на Letraset листи, \r\nсъдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни \r\nиздания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.</span> afasfas');

-- --------------------------------------------------------

--
-- Table structure for table `advertise`
--

CREATE TABLE `advertise` (
  `id` int(12) NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `site_link` text COLLATE utf8_unicode_ci,
  `dobaven_na` text COLLATE utf8_unicode_ci,
  `banner_img` text COLLATE utf8_unicode_ci,
  `expire` text COLLATE utf8_unicode_ci,
  `link_title` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(12) NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `site_link` text COLLATE utf8_unicode_ci,
  `banner_img` text COLLATE utf8_unicode_ci,
  `link_title` text COLLATE utf8_unicode_ci,
  `avtor` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `author` text NOT NULL,
  `text` text NOT NULL,
  `date` text NOT NULL,
  `avatar` text NOT NULL,
  `nick_colour` text NOT NULL,
  `user_id` text NOT NULL,
  `newsid` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(10) NOT NULL,
  `site_name` text NOT NULL,
  `logo_text_small` text NOT NULL,
  `logo_text_big` text NOT NULL,
  `favicon` text NOT NULL,
  `admin_email` text NOT NULL,
  `chat_enable` text NOT NULL,
  `gallery_enable` text NOT NULL,
  `img_upload_enable` text NOT NULL,
  `file_upload_enable` text NOT NULL,
  `poll_enable` text NOT NULL,
  `footer_stats_enable` text NOT NULL,
  `socials_enable` text NOT NULL,
  `fb_link` text NOT NULL,
  `tw_link` text NOT NULL,
  `goo_link` text NOT NULL,
  `servers_enable` text NOT NULL,
  `default_language` text NOT NULL,
  `head_box_text` text NOT NULL,
  `last_news_link` text NOT NULL,
  `last_news_name` text NOT NULL,
  `google_analytics` text,
  `google_site_verify` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `site_name`, `logo_text_small`, `logo_text_big`, `favicon`, `admin_email`, `chat_enable`, `gallery_enable`, `img_upload_enable`, `file_upload_enable`, `poll_enable`, `footer_stats_enable`, `socials_enable`, `fb_link`, `tw_link`, `goo_link`, `servers_enable`, `default_language`, `head_box_text`, `last_news_link`, `last_news_name`, `google_analytics`, `google_site_verify`) VALUES
(1, 'Case19', 'Случай 19', 'Case 19', 'template/assets/img/favicon.ico', 'admin@webocean.info', '1', '1', '1', '1', '1', '1', '1', 'http://facebook.com', 'http://twitter.com', 'http://google.bg', '1', 'bg', 'Система за управляване на съдържанието', 'https://webocean.info/', 'WebOcean.INFO', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(12) NOT NULL,
  `date` text COLLATE utf8_unicode_ci,
  `ip` text COLLATE utf8_unicode_ci,
  `username` text COLLATE utf8_unicode_ci,
  `text` text COLLATE utf8_unicode_ci,
  `question` text COLLATE utf8_unicode_ci,
  `email` text COLLATE utf8_unicode_ci,
  `respond` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dpolls`
--

CREATE TABLE `dpolls` (
  `id` int(12) NOT NULL,
  `poll_question` text NOT NULL,
  `poll_answer` text NOT NULL,
  `poll_votes` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dpolls_votes`
--

CREATE TABLE `dpolls_votes` (
  `id` int(12) NOT NULL,
  `poll_id` text NOT NULL,
  `ip` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(12) NOT NULL,
  `date` text COLLATE utf8_unicode_ci,
  `uploader` text COLLATE utf8_unicode_ci,
  `pic_link` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `greyfish_servers`
--

CREATE TABLE `greyfish_servers` (
  `id` int(12) NOT NULL,
  `ip` text NOT NULL,
  `port` text NOT NULL,
  `players` text NOT NULL,
  `maxplayers` text NOT NULL,
  `version` text NOT NULL,
  `type` text NOT NULL,
  `map` text NOT NULL,
  `hostname` text NOT NULL,
  `vote` text NOT NULL,
  `status` text NOT NULL,
  `last_update` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `greyfish_servers`
--

INSERT INTO `greyfish_servers` (`id`, `ip`, `port`, `players`, `maxplayers`, `version`, `type`, `map`, `hostname`, `vote`, `status`, `last_update`) VALUES
(2, '87.98.241.203', '27015', '30', '32', 'CS 1.6', 'cs', 'de_barcelona', '-= RESPAWN.WESTCSTRIKE.RO # THE BEST SERVER RESPAWN =-', '0', '1', 1601049071),
(3, '216.52.148.47', '27015', '64', '64', 'CS:GO', 'csgo', 'ze_diddle_v3', '[GFLClan.com] Zombie Escape 24/7 | Rank | Recruiting | NoBlock ', '0', '1', 1601049071);

-- --------------------------------------------------------

--
-- Table structure for table `jquery_js`
--

CREATE TABLE `jquery_js` (
  `jquery_js` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jquery_js`
--

INSERT INTO `jquery_js` (`jquery_js`) VALUES
('function TopNav() {\r\n  var x = document.getElementById(\"myTopnav\");\r\n  if (x.className === \"topnav\") {\r\n    x.className += \" responsive\";\r\n  } else {\r\n    x.className = \"topnav\";\r\n  }\r\n}');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(12) NOT NULL,
  `title` text NOT NULL,
  `the_content` text NOT NULL,
  `position` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `the_content`, `position`) VALUES
(9, 'WEBOcean.INFO', 'WebOcean.INFO има за цел да предоставя възможност на всеки да сподели своите мисли и опит, както и да обшува с останалите потребители - информацията трябва да е за всеки и всички са добре дошли да я споделят.', 'right');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(10) NOT NULL,
  `author` text NOT NULL,
  `title` text NOT NULL,
  `seourl` text NOT NULL,
  `text` text NOT NULL,
  `date` varchar(128) DEFAULT NULL,
  `comments` int(3) DEFAULT NULL,
  `comments_enabled` varchar(128) DEFAULT NULL,
  `img` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(12) NOT NULL,
  `page_name` text NOT NULL,
  `page_title` text NOT NULL,
  `menu_type` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(12) NOT NULL,
  `date` date DEFAULT NULL,
  `ip` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stats`
--

INSERT INTO `stats` (`id`, `date`, `ip`) VALUES
(4, '2020-09-23', '::1'),
(5, '2020-09-24', '::1'),
(6, '2020-09-25', '::1'),
(7, '2020-09-25', '::1'),
(8, '2020-09-26', '::1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertise`
--
ALTER TABLE `advertise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dpolls`
--
ALTER TABLE `dpolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dpolls_votes`
--
ALTER TABLE `dpolls_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `greyfish_servers`
--
ALTER TABLE `greyfish_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertise`
--
ALTER TABLE `advertise`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dpolls`
--
ALTER TABLE `dpolls`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dpolls_votes`
--
ALTER TABLE `dpolls_votes`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `greyfish_servers`
--
ALTER TABLE `greyfish_servers`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
