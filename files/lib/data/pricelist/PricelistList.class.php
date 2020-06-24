<?php
namespace wcf\data\pricelist;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of Pricelists.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
	public $className = Pricelist::class;
}
