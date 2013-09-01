<?php

/**
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
 
array_insert($GLOBALS['FE_MOD']['navigationMenu'],0, array
    (
        'megamenu' => 'ModuleMegaMenu'
    ));
