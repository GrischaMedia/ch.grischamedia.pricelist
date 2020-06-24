<?php
namespace wcf\page;
use wcf\data\category\CategoryList;
use wcf\data\pricelist\Pricelist;
use wcf\data\pricelist\PricelistList;
use wcf\system\category\CategoryHandler;
use wcf\system\WCF;
use wcf\util\MessageUtil;
use wcf\util\StringUtil;

/**
 * Shows the Pricelist
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
class PricelistPage extends AbstractPage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'ch.grischamedia.pricelist.PricelistPage';
	public $neededPermissions = ['user.pricelist.canEdit', 'user.pricelist.canSee'];
	
	/**
	 * categories and items
	 */
	public $categories = null;
	public $items = null;
	public $itemsToCategory = [];
	
	/**
	 * sorting
	 */
	public $defaultSortField = 'title';
	public $defaultSortOrder = 'ASC';
	public $sortField = '';
	public $sortOrder = '';
	public $validSortFields = ['priceID', 'title', 'description', 'itemID', 'currency', 'price'];
	
	/**
	 * search
	 */
	public $search = '';
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		$this->validateSortOrder();
		$this->validateSortField();
		
		parent::readData();
		
		$objectType = CategoryHandler::getInstance()->getObjectTypeByName('ch.grischamedia.pricelist.category');
		if ($objectType) {
			$categoryList = new CategoryList();
			$categoryList->getConditionBuilder()->add('category.objectTypeID = ?', [$objectType->objectTypeID]);
			$categoryList->sqlOrderBy = 'showOrder ASC';
			$categoryList->readObjects();
			$this->categories = $categoryList->getObjects();
		}
		
		// get all items, sorted
		$sqlOrderBy = $this->sortField." ".$this->sortOrder;
		
		$itemList = new PricelistList();
		// search
		if ($this->search) {
			$search = '%'.$this->search.'%';
			$itemList->getConditionBuilder()->add('(title LIKE ? OR description LIKE ? OR itemID LIKE ?)', [$search, $search, $search]);
		}
		// disabled
		if (!WCF::getSession()->getPermission('user.pricelist.canEdit')) {
			$itemList->getConditionBuilder()->add('isDisabled = ?', [0]);
		}
		
		if ($this->sortOrder) {
			$itemList->sqlOrderBy = $sqlOrderBy;
		}
		$itemList->readObjects();
		$this->items = $itemList->getObjects();
		
		// assign to category
		if (count($this->items)) {
			foreach ($this->items as $item) {
				if (!$item->categoryID) {
					$this->itemsToCategory[0][] = $item;
				}
				else {
					$this->itemsToCategory[$item->categoryID][] = $item;
				}
			}
		}
		
		// sort
		$test = [];
		if (isset($this->itemsToCategory[0])) {
			foreach ($this->itemsToCategory[0] as $item) {
				$test[0][] = $item;
			}
		}
		
		foreach ($this->categories as $category) {
			if (isset($this->itemsToCategory[$category->categoryID])) {
				foreach ($this->itemsToCategory[$category->categoryID] as $item) {
					$item->truncated = '';
					$item->description = $item->getFormattedDescription();
					if (mb_strlen($item->description) > PRICELIST_EXCERPT_LENGTH) {
						$item->truncated = $item->getFormattedExcerpt(PRICELIST_EXCERPT_LENGTH);
					}
					$test[$category->categoryID][] = $item;
				}
			}
		}
		
		$this->itemsToCategory = $test;
	}
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		// read sorting parameter
		if (isset($_REQUEST['sortField'])) $this->sortField = $_REQUEST['sortField'];
		if (isset($_REQUEST['sortOrder'])) $this->sortOrder = $_REQUEST['sortOrder'];
		
		// read search
		if (!empty($_REQUEST['search'])) $this->search = StringUtil::trim($_REQUEST['search']);
		
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables () {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'categories' => $this->categories,
				'items' => $this->items,
				'itemsToCategory' => $this->itemsToCategory,
				'sortField' => $this->sortField,
				'sortOrder' => $this->sortOrder,
				'search' => $this->search
		]);
	}
	
	/**
	 * Validates the given sort field parameter.
	 */
	public function validateSortField() {
		if (!in_array($this->sortField, $this->validSortFields)) {
			$this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * Validates the given sort order.
	 */
	public function validateSortOrder() {
		switch ($this->sortOrder) {
			case 'ASC':
			case 'DESC':
				break;
			default:
				$this->sortOrder = $this->defaultSortOrder;
		}
	}
}
