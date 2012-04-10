<?php if(!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * c4y_megamenu
 * Wrap an article into a navigation
 *
 * PHP version 5
 * @copyright  contao4you | Oliver Lohoff 2011
 * @author     Oliver Lohoff <info@contao4you.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
 

$GLOBALS['TL_LANG']['tl_page']['megamenu'] = array('Mega-Menu', 'Here you can assign an Article to the Menu. The Article will be shown as a Submenu in the next Level. The other Entries in the Sitestructure will then not been recogniced.');
$GLOBALS['TL_LANG']['tl_page']['mm_article'] = array('Article', 'Here you can assign an Article to the Menu.');
$GLOBALS['TL_LANG']['tl_page']['mm_col'] = array('Navi-Area', 'Please fill in the Area where to find the Navigation (e.g. main, header, left, right, footer).');
$GLOBALS['TL_LANG']['tl_page']['mm_cssID'] = array('CSS ID/class', 'Here you can set an ID and one or more classes.');
$GLOBALS['TL_LANG']['tl_page']['noLink'] = array('Link remove', 'Remove\'s the Link from the Menu.');

//Legenden
$GLOBALS['TL_LANG']['tl_page']['megamenu_legend'] = 'Mega-Menu';
?>