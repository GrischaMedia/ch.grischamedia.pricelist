<?php 
namespace wcf\data\pricelist;
use wcf\data\DatabaseObject;
use wcf\system\label\object\PricelistLabelObjectHandler;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\request\IRouteController;
use wcf\system\WCF;
use wcf\util\MessageUtil;

/**
 * Represents a Pricelist
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class Pricelist extends DatabaseObject implements IRouteController {
	/**
	 * @inheritDoc
	 */
	protected static $databaseTableName = 'pricelist';
	protected static $databaseTableIndexName = 'priceID';
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->title);
	}
	
	/**
	 * get assigned labels
	 */
	public function getLabels() {
		$labels = PricelistLabelObjectHandler::getInstance()->getAssignedLabels([$this->priceID]);
		
		$data = [];
		foreach ($labels as $labelObjects) {
			foreach ($labelObjects as $label) {
				$data[] = $label;
			}
		}
		return $data;
	}
	
	/**
	 * Returns the truncated description.
	 */
	public function getFormattedDescription() {
		$processor = new HtmlOutputProcessor();
		$processor->process($this->description, 'ch.grischamedia.pricelist.message', $this->priceID);
		
		return $processor->getHtml();
	}
	
	/**
	 * Returns the truncated description.
	 */
	public function getFormattedExcerpt($maxLength = 250) {
		$processor = new HtmlOutputProcessor();
		$processor->process($this->description, 'ch.grischamedia.pricelist.message', $this->priceID);
		
		return MessageUtil::truncateFormattedMessage($processor->getHtml(), $maxLength);
	}
}
