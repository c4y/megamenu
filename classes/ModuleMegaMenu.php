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

namespace Contao4You;
 
class ModuleMegaMenu extends \ModuleNavigation
{

    /**
	 * Recursively compile the navigation menu and return it as HTML string
	 * @param integer
	 * @param integer
	 * @return string
	 */
	protected function renderNavigation($pid, $level=1, $host=null, $language=null)
	{
		// Get all active subpages
		$objSubpages = \PageModel::findPublishedSubpagesWithoutGuestsByPid($pid, $this->showHidden, $this instanceof \ModuleSitemap);

		if ($objSubpages === null)
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
		if ($this->navigationTpl == '')
		{
			$this->navigationTpl = 'nav_default';
		}

		$objTemplate = new \FrontendTemplate($this->navigationTpl);

		$objTemplate->type = get_class($this);
		$objTemplate->cssID = $this->cssID; // see #4897
		$objTemplate->level = 'level_' . $level++;

        /***************************************
         * Megamenu (Start)
         ***************************************/
        $objTemplate->moomenu_pageid = $this->Template->moomenu_id;
        $objTemplate->moomenu_aktiv = $this->Template->moomenu_aktiv;
		$objTemplate->moomenu_activestate = $this->Template->moomenu_activestate;
		$objTemplate->moomenu_fade = ($this->Template->moomenu_fade) ? "true" : "false";
		$objTemplate->moomenu_slide = ($this->Template->moomenu_slide) ? "true" : "false";
		$objTemplate->moomenu_speed = $this->Template->moomenu_speed;
		$objTemplate->moomenu_timeout = $this->Template->moomenu_timeout;
        $objTemplate->moomenu_id = "mm_" . $this->id;
        $objTemplate->id = 'id="mm_' . $this->id . '" ';
        if ($this->Template->moomenu_css) {
            $objCSS = new \FrontendTemplate("nav_mm_css");
            $GLOBALS['TL_HEAD'][] = $objCSS->parse();
        }
        /***************************************
         * Megamenu (Ende)
         ***************************************/

		// Get page object
		global $objPage;

		// Browse subpages
		while ($objSubpages->next())
		{
			// Skip hidden sitemap pages
			if ($this instanceof \ModuleSitemap && $objSubpages->sitemap == 'map_never')
			{
				continue;
			}

			$subitems = '';
			$_groups = deserialize($objSubpages->groups);

			// Override the domain (see #3765)
			if ($host !== null)
			{
				$objSubpages->domain = $host;
			}

			// Do not show protected pages unless a back end or front end user is logged in
			if (!$objSubpages->protected || BE_USER_LOGGED_IN || (is_array($_groups) && count(array_intersect($_groups, $groups))) || $this->showProtected || ($this instanceof \ModuleSitemap && $objSubpages->sitemap == 'map_always'))
			{
				// Check whether there will be subpages
				if ($objSubpages->subpages > 0 && (!$this->showLevel || $this->showLevel >= $level || (!$this->hardLimit && ($objPage->id == $objSubpages->id || in_array($objPage->id, $this->Database->getChildRecords($objSubpages->id, 'tl_page'))))))
				{
					$subitems = $this->renderNavigation($objSubpages->id, $level, $host, $language);
				}

				// Get href
				switch ($objSubpages->type)
				{
					case 'redirect':
						$href = $objSubpages->url;

						if (strncasecmp($href, 'mailto:', 7) === 0)
						{
							$href = \String::encodeEmail($href);
						}
						break;

					case 'forward':
						if ($objSubpages->jumpTo)
						{
							$objNext = $objSubpages->getRelated('jumpTo');
						}
						else
						{
							$objNext = \PageModel::findFirstPublishedRegularByPid($objSubpages->id);
						}

						if ($objNext !== null)
						{
							$strForceLang = null;
							$objNext->loadDetails();

							// Check the target page language (see #4706)
							if ($GLOBALS['TL_CONFIG']['addLanguageToUrl'])
							{
								$strForceLang = $objNext->language;
							}

							$href = $this->generateFrontendUrl($objNext->row(), null, $strForceLang);

							// Add the domain if it differs from the current one (see #3765)
							if ($objNext->domain != '' && $objNext->domain != \Environment::get('host'))
							{
								$href = (\Environment::get('ssl') ? 'https://' : 'http://') . $objNext->domain . TL_PATH . '/' . $href;
							}
							break;
						}
						// DO NOT ADD A break; STATEMENT

					default:
						$href = $this->generateFrontendUrl($objSubpages->row(), null, $language);

						// Add the domain if it differs from the current one (see #3765)
						if ($objSubpages->domain != '' && $objSubpages->domain != \Environment::get('host'))
						{
							$href = (\Environment::get('ssl') ? 'https://' : 'http://') . $objSubpages->domain . TL_PATH . '/' . $href;
						}
						break;
				}

				// Active page
				if (($objPage->id == $objSubpages->id || $objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) && !$this instanceof \ModuleSitemap && !\Input::get('articles'))
				{
					// Mark active forward pages (see #4822)
					$strClass = (($objSubpages->type == 'forward' && $objPage->id == $objSubpages->jumpTo) ? 'forward' . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : '') : 'active') . (($subitems != '') ? ' submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '');
					$row = $objSubpages->row();

					$row['isActive'] = true;
					$row['subitems'] = $subitems;
					$row['class'] = trim($strClass);
					$row['title'] = specialchars($objSubpages->title, true);
					$row['pageTitle'] = specialchars($objSubpages->pageTitle, true);
					$row['link'] = $objSubpages->title;
					$row['href'] = $href;
					$row['nofollow'] = (strncmp($objSubpages->robots, 'noindex', 7) === 0);
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);

                    /***************************************
                     * Megamenu (Start)
                     ***************************************/
                    $row['megamenu'] = $objSubpages->megamenu;
                    $row['megamenu_article'] = $objSubpages->megamenu ? $this->getArticle($objSubpages->mm_article, true, true, $objSubpages->mm_col) : '';
                    //$arrCSS = deserialize($objSubpages->mm_cssID, false);
                    $row['megamenu_id'] = $arrCSS[0];
                    $row['megamenu_class'] = ($arrCSS[1] != '') ? ' ' . $arrCSS[1] : '';
                    /***************************************
                     * Megamenu (Ende)
                     ***************************************/

					// Override the link target
					if ($objSubpages->type == 'redirect' && $objSubpages->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}

				// Regular page
				else
				{
					$strClass = (($subitems != '') ? 'submenu' : '') . ($objSubpages->protected ? ' protected' : '') . (in_array($objSubpages->id, $objPage->trail) ? ' trail' : '') . (($objSubpages->cssClass != '') ? ' ' . $objSubpages->cssClass : '');

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
					$row['target'] = '';
					$row['description'] = str_replace(array("\n", "\r"), array(' ' , ''), $objSubpages->description);

                    /***************************************
                     * Megamenu (Start)
                     ***************************************/
                    $row['megamenu'] = $objSubpages->megamenu;
                    $row['megamenu_article'] = $objSubpages->megamenu ? $this->getArticle($objSubpages->mm_article, false, true, $objSubpages->mm_col) : '';
                    $row['megamenu_id'] = $arrCSS[0];
                    $row['megamenu_class'] = ($arrCSS[1] != '') ? ' ' . $arrCSS[1] : '';
                    /***************************************
                     * Megamenu (Ende)
                     ***************************************/

					// Override the link target
					if ($objSubpages->type == 'redirect' && $objSubpages->target)
					{
						$row['target'] = ($objPage->outputFormat == 'xhtml') ? ' onclick="return !window.open(this.href)"' : ' target="_blank"';
					}

					$items[] = $row;
				}
			}
		}

		// Add classes first and last
		if (!empty($items))
		{
			$last = count($items) - 1;

			$items[0]['class'] = trim($items[0]['class'] . ' first');
			$items[$last]['class'] = trim($items[$last]['class'] . ' last');
		}

		$objTemplate->items = $items;
		return !empty($items) ? $objTemplate->parse() : '';
	}



}
