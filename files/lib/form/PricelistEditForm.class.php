<?php
namespace wcf\form;
use wcf\data\pricelist\Pricelist;
use wcf\data\pricelist\PricelistAction;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\label\object\PricelistLabelObjectHandler;
use wcf\system\WCF;

/**
 * Shows the Pricelist edit form.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistEditForm extends PricelistAddForm {
	/**
	 * price data
	 */
	public $priceID = 0;
	public $priceObj = null;
	public $oldItemID = '';
	
	public $action = 'edit';
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->priceID = intval($_REQUEST['id']);
		$this->priceObj = new Pricelist($this->priceID);
		if (!$this->priceObj->priceID) {
			throw new IllegalLinkException();
		}
		
		$this->oldItemID = $this->priceObj->itemID;
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		AbstractForm::save();
		
		// update pricelist
		$this->objectAction = new PricelistAction([$this->priceID], 'update', [
				'data' => array_merge($this->additionalFields, [
						'itemID' => $this->itemID,
						'categoryID' => $this->categoryID ? $this->categoryID : null,
						'title' => $this->title,
						'description' => $this->description,
						'currency' => $this->currency,
						'price' => $this->price,
						
						'time' => TIME_NOW,
						'userID' => WCF::getUser()->userID,
						
						'hasLabels' => count($this->pricelistLabelIDs) ? 1 : 0
				]),
				'pricelistLabelIDs' => $this->pricelistLabelIDs,
				'htmlInputProcessor' => $this->htmlInputProcessor
		]);
		$this->objectAction->executeAction();
		
		$this->saved();
		
		// show success
		WCF::getTPL()->assign([
				'success' => true
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			$this->itemID = $this->priceObj->itemID;
			$this->categoryID = $this->priceObj->categoryID;
			$this->title = $this->priceObj->title;
			$this->description = $this->priceObj->description;
			$this->currency = $this->priceObj->currency;
			$this->price = $this->priceObj->price;
			
			// labels 
			$assignedLabels = PricelistLabelObjectHandler::getInstance()->getAssignedLabels([$this->priceObj->priceID], true);
			if (isset($assignedLabels[$this->priceObj->priceID])) {
				foreach ($assignedLabels[$this->priceObj->priceID] as $label) {
					$this->pricelistLabelIDs[$label->groupID] = $label->labelID;
				}
			}
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'priceObj' => $this->priceObj,
				'action' => 'edit'
		]);
	}
}
