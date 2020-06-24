<?php
namespace wcf\acp\form;

/**
 * Shows the Pricelist category add form.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistCategoryAddForm extends AbstractCategoryAddForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.pricelist.category.add';
	public $objectTypeName = 'ch.grischamedia.pricelist.category';

}
