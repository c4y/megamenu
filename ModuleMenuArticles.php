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

class ModuleMenuArticles extends Backend
{
	 /**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	/**
	 * Get all articles and return them as array (article alias)
	 * @param object
	 * @return array
	 */
	public function getArticles(DataContainer $dc)
	{
		$arrPids = array();
		$arrAlias = array();

		if (!$this->User->isAdmin)
		{
			foreach ($this->User->pagemounts as $id)
			{
				$arrPids[] = $id;
				$arrPids = array_merge($arrPids, $this->getChildRecords($id, 'tl_page', true));
			}

			if (empty($arrPids))
			{
				return $arrAlias;
			}

			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title 
												  AS parent 
												  FROM tl_article a 
												  LEFT JOIN tl_page p 
												  ON p.id=a.pid 
												  WHERE a.pid IN(". implode(',', array_map('intval', array_unique($arrPids))) .") 
												  ORDER BY parent, a.sorting")
									   ->execute($dc->id);
		}
		else
		{
			$objAlias = $this->Database->prepare("SELECT a.id, a.title, a.inColumn, p.title 
												  AS parent 
												  FROM tl_article a 
												  LEFT JOIN tl_page p 
												  ON p.id=a.pid 
												  ORDER BY parent, a.sorting")
									   ->execute($dc->id);
		}

		if ($objAlias->numRows)
		{
			$this->loadLanguageFile('tl_article');

			while ($objAlias->next())
			{
				$arrAlias[$objAlias->parent][$objAlias->id] = $objAlias->title . ' (' . (strlen($GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn]) ? $GLOBALS['TL_LANG']['tl_article'][$objAlias->inColumn] : $objAlias->inColumn) . ', ID ' . $objAlias->id . ')';
			}
		}

		return $arrAlias;
	}
	
	/**
	 * Return the edit article teaser wizard
	 * @param object
	 * @return string
	 */
	public function editArticle(DataContainer $dc)
	{
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=article&amp;table=tl_content&amp;id=' . $dc->value . '" title="'.sprintf(specialchars($GLOBALS['TL_LANG']['tl_content']['editarticle'][1]), $dc->value).'">' . $this->generateImage('alias.gif', $GLOBALS['TL_LANG']['tl_content']['editarticle'][0], 'style="vertical-align:top;"') . '</a>';
	}
}

?>