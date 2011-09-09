-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 07. Januar 2006 um 18:01
-- Server Version: 4.1.14
-- PHP-Version: 5.0.4
-- 
-- Datenbank: `monoseat_de`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cms_page`
-- 

CREATE TABLE `cms_page` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `parent` tinyint(3) unsigned NOT NULL default '1',
  `lft` mediumint(8) unsigned default NULL,
  `rgt` mediumint(8) unsigned default NULL,
  `url` varchar(255) default NULL,
  `title` varchar(127) default NULL,
  `content` text,
  `f_markup` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `title` (`url`),
  KEY `f_markup` (`f_markup`)
) ENGINE=InnoDB CHARSET=utf8;

-- 
-- Daten für Tabelle `cms_page`
-- 

INSERT INTO `cms_page` VALUES (1, 0, 1, 2, '', 'Home', 'First Page!!', 2);

-- 
-- Tabellenstruktur für Tabelle `cms_page`
-- 

CREATE TABLE `cms_markup` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `markup` varchar(127) default NULL,
  `name` varchar(127) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB CHARSET=utf8;

-- 
-- Daten für Tabelle `cms_markup`
-- 

INSERT INTO `cms_markup` VALUES (1, 'none', 'None');
INSERT INTO `cms_markup` VALUES (2, 'markdown', 'Markdown');
INSERT INTO `cms_markup` VALUES (3, 'textile', 'Textile');


-- 
-- Constraints der exportierten Tabellen
-- 

-- 
-- Constraints der Tabelle `cms_page`
-- 
ALTER TABLE `cms_page`
  ADD CONSTRAINT `cms_page_ibfk_1` FOREIGN KEY (`f_markup`) REFERENCES `cms_markup` (`id`);
