<?php
namespace wcf\system\category;
use wcf\system\category\AbstractCategoryType;
use wcf\system\WCF;

/**
 * Category type for Pricelist.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistCategoryType extends AbstractCategoryType {
	/**
	 * @inheritDoc
	 */
	protected $forceDescription = false;
	
	/**
	 * @inheritDoc
	 */
	protected $langVarPrefix = 'pricelist.category';
	
	/**
	 * @inheritDoc
	 */
	protected $maximumNestingLevel = 0;
	
	/**
	 * @inheritDoc
	 */
	public function canAddCategory() {
		return $this->canEditCategory();
	}
	
	/**
	 * @inheritDoc
	 */
	public function canDeleteCategory() {
		return $this->canEditCategory();
	}
	
	/**
	 * @inheritDoc
	 */
	public function canEditCategory() {
		return WCF::getSession()->getPermission('admin.pricelist.canManage');
	}
}
