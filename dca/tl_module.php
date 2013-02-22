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

array_insert($GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'],0, 'moomenu_aktiv');
$GLOBALS['TL_DCA']['tl_module']['palettes']['megamenu'] = '{title_legend},name,headline,type;{nav_legend},levelOffset,showLevel,hardLimit,showProtected;{moomenu_legend:hide},moomenu_aktiv;{reference_legend:hide},defineRoot;{template_legend:hide},navigationTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['moomenu_aktiv'] = 'moomenu_mode, moomenu_mooin, moomenu_mooout, moomenu_durationin, moomenu_durationout';

$GLOBALS['TL_DCA']['tl_module']['fields']['navigationTpl']['default'] = 'nav_mm';
array_insert($GLOBALS['TL_DCA']['tl_module']['fields'], 0, array
	(
		'moomenu_aktiv' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_aktiv'],
				'exclude'                 => true,
				'inputType'               => 'checkbox',
				'eval'                    => array('submitOnChange'=>true, 'isBoolean'=>true, 'mandatory'=>false),
                'sql'                     => "char(1) NOT NULL default ''"
			),
		'moomenu_id' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_id'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' =>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
			),
	    'moomenu_mode' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_mode'],
				'exclude'                 => true,
				'inputType'               => 'select',
		        'options'                 => array('fade', 'drop'),
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class' =>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
			),
		'moomenu_durationin' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_durationin'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'default'                 => '1000',
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
			),
		'moomenu_durationout' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_durationout'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'default'                 => '200',
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
        ),
		'moomenu_mooin' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_mooin'],
				'exclude'                 => true,
				'inputType'               => 'select',
				'options'                 => array('linear','cubin:in', 'cubic:out', 'quart:in', 'quart:out', 'quint:in', 'quint:out', 'expo:in', 'expo:out', 'circ:in', 'circ:out', 'bounce:in', 'bounce:out', 'elastic:in', 'elastic:out'),
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
        ),
		'moomenu_mooout' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_mooout'],
				'exclude'                 => true,
				'inputType'               => 'select',
				'options'                 => array('linear','cubin:in', 'cubic:out', 'quart:in', 'quart:out', 'quint:in', 'quint:out', 'expo:in', 'expo:out', 'circ:in', 'circ:out', 'bounce:in', 'bounce:out', 'elastic:in', 'elastic:out'),
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
			)
	)
);




?>