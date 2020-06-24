<?php
namespace wcf\data\pricelist;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IToggleAction;
use wcf\data\pricelist\Pricelist;
use wcf\data\pricelist\PricelistEditor;
use wcf\system\exception\IllegalLinkException;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\label\object\PricelistLabelObjectHandler;
use wcf\system\WCF;

/**
 * Executes Pricelist-related actions.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistAction extends AbstractDatabaseObjectAction implements IToggleAction {
	/**
	 * @inheritDoc
	 */
	protected $className = PricelistEditor::class;
	
	/**
	 * @inheritDoc
	 */
	protected $permissionsDelete = ['user.pricelist.canEdit'];
	protected $permissionsUpdate = ['user.pricelist.canEdit'];
	
	// item
	public $item;
	
	/**
	 * @inheritDoc
	 */
	public function create() {
		// create entry
		$data = $this->parameters['data'];
		
		// set default value
	//	if (!isset($data['enableHtml'])) $data['enableHtml'] = 1;
		
		if (!empty($this->parameters['htmlInputProcessor'])) {
			$data['description'] = $this->parameters['htmlInputProcessor']->getHtml();
		}
		
		$item = call_user_func([$this->className, 'create'], $data);
		$pricelistEditor = new PricelistEditor($item);
		
		// labels
		if (!empty($this->parameters['pricelistLabelIDs'])) PricelistLabelObjectHandler::getInstance()->setLabels($this->parameters['pricelistLabelIDs'], $item->priceID);
		
		return $item;
	}
	
	/**
	 * @inheritDoc
	 */
	public function update() {
		if (!empty($this->parameters['htmlInputProcessor'])) {
			$this->parameters['data']['description'] = $this->parameters['htmlInputProcessor']->getHtml();
		}
		
		parent::update();
		
		// update labels
		foreach ($this->getObjects() as $item) {
			PricelistLabelObjectHandler::getInstance()->setLabels($this->parameters['pricelistLabelIDs'], $item->priceID);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function validateToggle() {
		parent::validateUpdate();
	}
	
	/**
	 * @inheritDoc
	 */
	public function toggle() {
		foreach ($this->objects as $item) {
			$item->update([
					'isDisabled' => $item->isDisabled ? 0 : 1
			]);
		}
	}
	
	/**
	 * open description text
	 */
	public function validateOpen() {
		$this->item = new Pricelist($this->parameters['objectID']);
		if (!$this->item->priceID) {
			throw new IllegalLinkException();
		}
	}
	
	public function open() {
		return [
				'id' => $this->item->priceID,
				'text' => $this->item->getFormattedDescription()
		];
	}
}
