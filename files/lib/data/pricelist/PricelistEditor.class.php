<?php
namespace wcf\data\pricelist;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit Pricelists.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	public static $baseClass = Pricelist::class;
}
