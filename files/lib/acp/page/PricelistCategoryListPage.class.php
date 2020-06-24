<?php
namespace wcf\acp\page;

/**
 * Shows the Pricelist category list page.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistCategoryListPage extends AbstractCategoryListPage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.pricelist.category.list';
	public $objectTypeName = 'ch.grischamedia.pricelist.category';
}
