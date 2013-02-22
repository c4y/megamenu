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

array_insert($GLOBALS['TL_DCA']['tl_page']['palettes']['__selector__'],0,'megamenu');

$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] = str_replace('{meta_legend}', '{megamenu_legend:hide},megamenu,noLink;{meta_legend}', $GLOBALS['TL_DCA']['tl_page']['palettes']['regular']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['forward'] = str_replace('{meta_legend}', '{megamenu_legend:hide},megamenu,noLink;{meta_legend}', $GLOBALS['TL_DCA']['tl_page']['palettes']['forward']);
$GLOBALS['TL_DCA']['tl_page']['palettes']['redirect'] = str_replace('{meta_legend}', '{megamenu_legend:hide},megamenu,noLink;{meta_legend}', $GLOBALS['TL_DCA']['tl_page']['palettes']['redirect']);


$GLOBALS['TL_DCA']['tl_page']['subpalettes']['megamenu'] = 'mm_article,mm_cssID';

$GLOBALS['TL_DCA']['tl_page']['fields']['megamenu'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['megamenu'],
            'exclude'   => false,
            'inputType' => 'checkbox',
            'eval'      => array('mandatory'=>false, 'isBoolean'=>true, 'submitOnChange'=>true),
            'sql'       => "char(1) NOT NULL default ''"
        );
$GLOBALS['TL_DCA']['tl_page']['fields']['mm_article'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['mm_article'],
            'exclude'   => false,
            'inputType'               => 'select',
			'options_callback'        => array('ModuleMenuArticles', 'getArticles'),
			'eval'                    => array('mandatory'=>true, 'submitOnChange'=>true),
			'wizard'    => array
			(
				array('ModuleMenuArticles', 'editArticle')
			),
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        );
$GLOBALS['TL_DCA']['tl_page']['fields']['mm_col'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['mm_col'],
            'exclude'   => false,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>false, 'maxlength'=>255, 'decodeEntities'=>true),
            'sql'       => "varchar(255) NOT NULL default ''"
        );
$GLOBALS['TL_DCA']['tl_page']['fields']['mm_cssID'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['mm_cssID'],
            'exclude'   => true,
            'inputType' => 'text',
			'eval'      => array('multiple'=>true, 'size'=>2),
            'sql'       => "varchar(255) NOT NULL default ''"
        );
$GLOBALS['TL_DCA']['tl_page']['fields']['noLink'] = array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_page']['noLink'],
            'exclude'   => false,
            'inputType' => 'checkbox',
            'eval'      => array('mandatory'=>false, 'isBoolean'=>true),
            'sql'       => "char(1) NOT NULL default ''"
        );

?>