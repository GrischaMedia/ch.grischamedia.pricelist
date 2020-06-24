<?php
namespace wcf\system\page\handler;
use wcf\system\WCF;

/**
 * Page handler for Pricelist.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistPageHandler extends AbstractMenuPageHandler {
	/**
	 * @inheritDoc
	 */
	public function isVisible($objectID = null) {
		if (WCF::getSession()->getPermission('user.pricelist.canEdit')) return true;
		if (WCF::getSession()->getPermission('user.pricelist.canSee')) return true;
		
		return false;
	}
}
