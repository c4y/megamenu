<?php

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
 * @copyright  contao4you | Oliver Lohoff 2013
 * @author     Oliver Lohoff <info@contao4you.de>
 * @package    megamenu
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

array_insert($GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'],0, 'moomenu_aktiv');
$GLOBALS['TL_DCA']['tl_module']['palettes']['megamenu'] = '{title_legend},name,headline,type;{nav_legend},levelOffset,showLevel,hardLimit,showProtected;{moomenu_legend:hide},moomenu_aktiv;{reference_legend:hide},defineRoot;{template_legend:hide},navigationTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['moomenu_aktiv'] = 'moomenu_activestate, moomenu_timeout, moomenu_speed, moomenu_slide, moomenu_fade, , ,moomenu_css';

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
        'moomenu_activestate' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_activestate'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'eval'                    => array('maxlength'=>255, 'tl_class' =>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
			),
	    'moomenu_slide' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_slide'],
				'exclude'                 => true,
				'inputType'               => 'checkbox',
				'eval'                    => array('tl_class' =>'w50'),
                'sql'                     => "char(1) NOT NULL default ''"
			),
		'moomenu_fade' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_fade'],
				'exclude'                 => true,
				'inputType'               => 'checkbox',
				'eval'                    => array('tl_class'=>'w50'),
                'sql'                     => "char(1) NOT NULL default ''"
			),
		'moomenu_speed' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_speed'],
				'exclude'                 => true,
				'inputType'               => 'select',
				'options'                 => array("1","2","3","4","5","6","7","8","9"),
                'default'                 => "5",
				'eval'                    => array('tl_class'=>'clr'),
                'sql'                     => "char(1) NOT NULL default ''"
        ),
		'moomenu_timeout' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_timeout'],
				'exclude'                 => true,
				'inputType'               => 'text',
                'default'                 => 200,
				'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
                'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'moomenu_css' => array
			(
				'label'                   => &$GLOBALS['TL_LANG']['tl_module']['moomenu_css'],
				'exclude'                 => true,
				'inputType'               => 'checkbox',
				'eval'                    => array('tl_class'=>'w50'),
                'sql'                     => "char(1) NOT NULL default ''"
        )
	)
);