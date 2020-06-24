<?php
namespace wcf\acp\form;

/**
 * Shows the Pricelist category edit form.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistCategoryEditForm extends AbstractCategoryEditForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.pricelist.category.list';
	public $objectTypeName = 'ch.grischamedia.pricelist.category';
}
