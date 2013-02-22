<?php

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Contao4You',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'Contao4You\ModuleMegaMenu'     => 'system/modules/megamenu/classes/ModuleMegaMenu.php',
	'Contao4You\ModuleMenuArticles' => 'system/modules/megamenu/modules/ModuleMenuArticles.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nav_mm'        => 'system/modules/megamenu/templates'
));
