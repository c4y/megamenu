<?php if(!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 *
 * c4y_megamenu
 * Wrap an article into a navigation
 *
 * PHP version 5
 * @copyright  contao4you | Oliver Lohoff 2011
 * @author     Oliver Lohoff <info@contao4you.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
 
array_insert($GLOBALS['FE_MOD']['navigationMenu'],0, array
    (
        'megamenu' => 'ModuleMegaMenu'
    ));

?>