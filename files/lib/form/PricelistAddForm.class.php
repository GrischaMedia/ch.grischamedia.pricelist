<?php
namespace wcf\form;
use wcf\data\category\Category;
use wcf\data\category\CategoryNodeTree;
use wcf\data\pricelist\PricelistAction;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\label\LabelHandler;
use wcf\system\page\PageLocationManager;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the Pricelist add form.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistAddForm extends AbstractForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'ch.grischamedia.pricelist.PricelistPage';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['user.pricelist.canEdit'];
	
	/**
	 * @inheritDoc
	 */
	public $loginRequired = true;
	
	/**
	 * @inheritDoc
	 */
	public $messageObjectType = 'ch.grischamedia.pricelist.message';
	
	/**
	 * @var HtmlInputProcessor
	 */
	public $htmlInputProcessor;
	
	/**
	 * pricelist data
	 */
	public $itemID = '';
	public $categoryID = 0;
	public $title = '';
	public $description = '';
	public $currency = '';
	public $price = '';
	
	/**
	 * others
	 */
	public $categoryNodeTree = null;
	public $availableCurrencies = [];
	public $categoryWarning = 1;
	public $currencyWarning = 1;
	
	public $action = 'add';
	public $oldItemID = '';
	
	/**
	 * Labels
	 */
	public $availableLabels = [];
	public $labelGroups;
	public $pricelistLabelIDs = [];
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get available currencies
		$this->availableCurrencies = explode("\n", StringUtil::unifyNewlines(StringUtil::trim(PRICELIST_AVAILABLE_CURRENCIES)));
		if (!empty($this->availableCurrencies[0])) {
			$this->currencyWarning = 0;
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['itemID'])) $this->itemID = StringUtil::trim($_POST['itemID']);
		if (isset($_POST['categoryID'])) $this->categoryID = intval($_POST['categoryID']);
		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['description'])) $this->description = StringUtil::trim($_POST['description']);
		if (isset($_POST['currency'])) $this->currency = $_POST['currency'];
		if (isset($_POST['price'])) {
			$this->price = StringUtil::trim($_POST['price']);
			$this->price = str_replace(WCF::getLanguage()->get('wcf.global.thousandsSeparator'), '', $this->price);
			if (WCF::getLanguage()->get('wcf.global.decimalPoint') != '.') $this->price = str_replace(WCF::getLanguage()->get('wcf.global.decimalPoint'), '.', $this->price);
			$this->price = floatval($this->price);
		}
		
		if (isset($_POST['pricelistLabelIDs']) && is_array($_POST['pricelistLabelIDs'])) $this->pricelistLabelIDs = $_POST['pricelistLabelIDs'];
		else $this->pricelistLabelIDs = [];
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();
		
		// title and description
		if (empty($this->title)) {
			throw new UserInputException('title', 'empty');
		}
		if (empty($this->description)) {
			throw new UserInputException('description', 'empty');
		}
		
		$this->htmlInputProcessor = new HtmlInputProcessor();
		$this->htmlInputProcessor->process($this->description, $this->messageObjectType, 0);
		
		if ($this->htmlInputProcessor->appearsToBeEmpty()) {
			throw new UserInputException('description', 'empty');
		}
		$description = $this->htmlInputProcessor->getTextContent();
		if (mb_strlen($description) > 60000) {
			throw new UserInputException('description', 'tooLong');
		}
		
		// itemID
		if (empty($this->itemID)) {
			throw new UserInputException('itemID', 'empty');
		}
		
		if ($this->oldItemID != $this->itemID) {
			$itemIDs = [];
			$sql = "SELECT		itemID
					FROM		wcf".WCF_N."_pricelist";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute();
			while ($row = $statement->fetchArray()) {
				$itemIDs[] = $row['itemID'];
			}
			if (in_array($this->itemID, $itemIDs)) {
				throw new UserInputException('itemID', 'exists');
			}
		}
		
		// category
		$category = new Category($this->categoryID);
		if (!$category->categoryID) {
			throw new UserInputException('categoryID', 'invalid');
		}
		
		// currency and price
		if (empty($this->currency)) {
			throw new UserInputException('currency');
		}
		if (!in_array($this->currency, $this->availableCurrencies)) {
			throw new UserInputException('currency', 'invalid');
		}
		
		if (!$this->price) {
			throw new UserInputException('price', 'invalid');
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();
		
		// save title
		$this->objectAction = new PricelistAction([], 'create', [
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
		
		// reset values
		$this->title = $this->description = $this->itemID = $this->price = '';
		$this->categoryID = 0;
		$this->pricelistLabelIDs = [];
		
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
		
		// categories
		$this->categoryNodeTree = new CategoryNodeTree('ch.grischamedia.pricelist.category', 0, true);
		foreach ($this->categoryNodeTree->getIterator() as $category) {
			if (!$category->isDisabled) {
				$this->categoryWarning = 0;
				break;
			}
		}
		
		// labels
		$sql = "SELECT		label.*, label_group.groupName
				FROM		wcf".WCF_N."_label label
				LEFT JOIN	wcf".WCF_N."_label_group label_group
							ON (label.groupID = label_group.groupID)
				ORDER BY	label_group.groupName ASC, label.label ASC";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			$this->availableLabels[] = $row;
		}
		// labels
		$this->labelGroups = LabelHandler::getInstance()->getLabelGroups([], false);
		
		// add breadcrumbs
		PageLocationManager::getInstance()->addParentLocation('ch.grischamedia.pricelist.PricelistPage');
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'action' => 'add',
				'availableCurrencies' => $this->availableCurrencies,
				'categoryNodeList' => $this->categoryNodeTree->getIterator(),
				
				'itemID' => $this->itemID,
				'categoryID' => $this->categoryID,
				'categoryWarning' => $this->categoryWarning,
				'title' => $this->title,
				'description' => $this->description,
				'currency' => $this->currency,
				'currencyWarning' => $this->currencyWarning,
				'price' => $this->price,
				'userID' => WCF::getUser()->userID,
				'time' => TIME_NOW,
				'availableLabels' => $this->availableLabels,
				'labelGroups' => $this->labelGroups,
				'pricelistLabelIDs' => $this->pricelistLabelIDs,
				
		]);
	}
}
