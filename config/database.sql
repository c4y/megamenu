-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_calendar`
-- 


CREATE TABLE `tl_page` (
  `megamenu` char(1) NOT NULL default '',
  `mm_article` int(10) unsigned NOT NULL default '0',
  `mm_col` varchar(255) NOT NULL default '',
  `mm_cssID` varchar(255) NOT NULL default '',
  `noLink` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_module` (
  `moomenu_aktiv` char(1) NOT NULL default '',
  `moomenu_id` varchar(255) NOT NULL default '',
  `moomenu_mode` varchar(255) NOT NULL default '',
  `moomenu_mooin` varchar(255) NOT NULL default '',
  `moomenu_mooout` varchar(255) NOT NULL default '',
  `moomenu_durationin` varchar(255) NOT NULL default '',
  `moomenu_durationout` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;