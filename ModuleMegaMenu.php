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
 
 
class ModuleMegaMenu extends ModuleNavigation
{

    /**
	 * Recursively compile the navigation menu and return it as HTML string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	 
	protected function renderNavigation($pid, $level=1)
	{
		$time = time();

		// Get all active subpages
		$objSubpages = $this->Database->prepare("SELECT p1.*, (SELECT COUNT(*) FROM tl_page p2 WHERE p2.pid=p1.id AND p2.type!='root' AND p2.type!='error_403' AND p2.type!='error_404'" . (!$this->showHidden ? (($this instanceof ModuleSitemap) ? " AND (p2.hide!=1 OR sitemap='map_always')" : " AND p2.hide!=1") : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND p2.guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (p2.start='' OR p2.start<".$time.") AND (p2.stop='' OR p2.stop>".$time.") AND p2.published=1" : "") . ") AS subpages FROM tl_page p1 WHERE p1.pid=? AND p1.type!='root' AND p1.type!='error_403' AND p1.type!='error_404'" . (!$this->showHidden ? (($this instanceof ModuleSitemap) ? " AND (p1.hide!=1 OR sitemap='map_always')" : " AND p1.hide!=1") : "") . ((FE_USER_LOGGED_IN && !BE_USER_LOGGED_IN) ? " AND p1.guests!=1" : "") . (!BE_USER_LOGGED_IN ? " AND (p1.start='' OR p1.start<".$time.") AND (p1.stop='' OR p1.stop>".$time.") AND p1.published=1" : "") . " ORDER BY p1.sorting")
									  ->execute($pid);

		if ($objSubpages->numRows < 1)
		{
			return '';
		}

		$items = array();
		$groups = array();

		// Get all groups of the current front end user
		if (FE_USER_LOGGED_IN)
		{
			$this->import('FrontendUser', 'User');
			$groups = $this->User->groups;
		}

		// Layout template fallback
		if (!strlen($this->navigationTpl))
		{
			$this->navigationTpl = 'nav_mm';
		}

		$objTemplate = new FrontendTemplate($this->navigationTpl);
		//$this->Template->cssID = strlen($this->cssID[0]) ? ' id="' . $this->cssID[0] . '"' : '';
		//$this->Template->cssID = strlen($this->cssID[0]) ? ' id="' . $this->cssID[0] . ' moomenu' . $this->Template->id . '"' : 'moomenu';
		//$this->Template->cssID = "test";

		$objTemplate->type = get_class($this);
		$objTemplate->level = 'level_' . $level++;
		$objTemplate->moduleid = $this->Template->id;

		$objTemplate->moomenu_aktiv = $this->Template->moomenu_aktiv;
		//$objTemplate->moomenu_id = $this->Template->moomenu_id;
        $objTemplate->moomenu_id = "mm_" . $this->id;
		$objTemplate->moomenu_mode = $this->Template->moomenu_mode;
		$objTemplate->moomenu_durationin = $this->Template->moomenu_durationin;
		$objTemplate->moomenu_durationout = $this->Template->moomenu_durationout;
		$objTemplate->moomenu_mooin = $this->Template->moomenu_mooin;
		$objTemplate->moomenu_mooout = $this->Template->moomenu_mooout;
        $objTemplate->id = 'id="mm_' . $this->id . '" ';

		// Get page object
		global $objPage;

		// Browse subpages
		while($objSubpages->next())
		{
			// Skip hidden sitemap pages
			if ($this instanceof ModuleSitemap && $objSubpages->sitemap == 'map_never')
			{
				continue;
			}

			$subitems = '';
			$_groups = deserialize($objSubpages->groups);

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected || ($this instanceof ModuleSitemap && $objSubpages->sitemap == 'map_always'))
			{
				// Check whether there will be subpages
				if ($objSubpages->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->getChildRecords($objSubpages->id, 'tl_page', true))))))
				{
					$subitems = $this->renderNavigation($objSubpages->id, $level);
				}

				// Get href
				switch ($objSubpages->type)
				{
					case 'redirect':
						$href = $objSubpages->url;

						if (strncasecmp($href, 'mailto:', 7) === 0)
						{
							$this->import('String');
							$href = $this->String->encodeEmail($href);
						}
						break;

					case 'forward':
						if (!$objSubpages->jumpTo)
						{
							$objNext = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE pid=? AND type='regular'" . (!BE_USER_LOGGED_IN ? " AND (start='' OR start<$time) AND (stop='' OR stop>$time) AND published=1" : "") . " ORDER BY sorting")
													  ->limit(1)
													  ->execute($objSubpages->id);
						}
						else
						{
							$objNext = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
													  ->limit(1)
													  ->execute($objSubpages->jumpTo);
						}

						if ($objNext->numRows)
						{
							$href = $this->generateFrontendUrl($objNext->fetchAssoc());
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($objSubpages->row());
						break;
				}

				$arrCSS = deserialize($objSubpages->mm_cssID, false);

				// Active page
				if (($objPage->id == $objSubpages->id || $objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) && !$this instanceof ModuleSitemap && !$this->Input->get('articles'))
				{
					// original
					$strClass = (($subitems != '' || $objSubpages->megamenu) ? 'submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '');
					//if ($arrCSS[1] != '') $strClass = $strClass . ' ' . $arrCSS[1];

					$row = $objSubpages->row();
					
					$row['isActive'] = true;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = (($objSubpages->type == 'redirect' && $objSubpages->target) ? LINK_NEW_WINDOW : '');
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);
                    // = $getArticle (id, false, false, column?);
                    $row['megamenu'] = $objSubpages->megamenu;
                    $row['megamenu_article'] = $objSubpages->megamenu ? $this->getArticle($objSubpages->mm_article, true, true, $objSubpages->mm_col) : '';
                    //$arrCSS = deserialize($objSubpages->mm_cssID, false);
                    $row['megamenu_id'] = $arrCSS[0];
                    $row['megamenu_class'] = ($arrCSS[1] != '') ? ' ' . $arrCSS[1] : '';

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = (($subitems != '' || $objSubpages->megamenu) ? 'submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '') . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : '');
					//if ($arrCSS[1] != '') $strClass .= ' ' . $arrCSS[1];

					// Mark pages on the same level (see #2419)
					if ($objSubpages->pid == $objPage->pid)
					{
						$strClass .= ' sibling';
					}

					$row = $objSubpages->row();

					$row['isActive'] = false;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = (($objSubpages->type == 'redirect' && $objSubpages->target) ? LINK_NEW_WINDOW : '');
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);
                    $row['megamenu'] = $objSubpages->megamenu;
                    $row['megamenu_article'] = $objSubpages->megamenu ? $this->getArticle($objSubpages->mm_article, false, true, $objSubpages->mm_col) : '';
                    //$arrCSS = deserialize($objSubpages->mm_cssID, false);
                    $row['megamenu_id'] = $arrCSS[0];
                    $row['megamenu_class'] = ($arrCSS[1] != '') ? ' ' . $arrCSS[1] : '';
					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		if (count($items))
		{
			$last = count($items) - 1;

			$items[0]['class'] = trim($items[0]['class'] . ' first');
			$items[$last]['class'] = trim($items[$last]['class'] . ' last');
		}

		$objTemplate->items = $items;
		return count($items) ? $objTemplate->parse() : '';
	}
}

?>