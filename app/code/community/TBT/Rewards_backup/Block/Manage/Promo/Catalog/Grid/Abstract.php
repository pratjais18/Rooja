<?php

/**
 * WDCA - Sweet Tooth
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the WDCA SWEET TOOTH POINTS AND REWARDS 
 * License, which extends the Open Software License (OSL 3.0).
 * The Sweet Tooth License is available at this URL: 
 * http://www.wdca.ca/sweet_tooth/sweet_tooth_license.txt
 * The Open Software License is available at this URL: 
 * http://opensource.org/licenses/osl-3.0.php
 * 
 * DISCLAIMER
 * 
 * By adding to, editing, or in any way modifying this code, WDCA is 
 * not held liable for any inconsistencies or abnormalities in the 
 * behaviour of this code. 
 * By adding to, editing, or in any way modifying this code, the Licensee
 * terminates any agreement of support offered by WDCA, outlined in the 
 * provided Sweet Tooth License. 
 * Upon discovery of modified code in the process of support, the Licensee 
 * is still held accountable for any and all billable time WDCA spent 
 * during the support process.
 * WDCA does not guarantee compatibility with any other framework extension. 
 * WDCA is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension.
 * If you did not receive a copy of the license, please send an email to 
 * contact@wdca.ca or call 1-888-699-WDCA(9322), so we can send you a copy 
 * immediately.
 * 
 * @category   [TBT]
 * @package    [TBT_Rewards]
 * @copyright  Copyright (c) 2009 Web Development Canada (http://www.wdca.ca)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Manage Promo Catalog Grid Abstract
 *
 * @category   TBT
 * @package    TBT_Rewards
 * @author     WDCA Sweet Tooth Team <contact@wdca.ca>
 */
abstract class TBT_Rewards_Block_Manage_Promo_Catalog_Grid_Abstract extends Mage_Adminhtml_Block_Widget_Grid {
	
	/**
	 * @param $ruleTypeId type id (redemption or distri?)
	 * @see TBT_Rewards_Helper_Rule_Type
	 */
	public function __construct($ruleTypeId) {
		parent::__construct ();
		$this->setId ( 'promo_catalog_grid' );
		$this->setDefaultSort ( 'name' );
		$this->setDefaultDir ( 'ASC' );
		$this->setRuleTypeId ( $ruleTypeId );
		$this->setSaveParametersInSession ( true );
	}
	
	/**
	 * Fetches the rule type helper;
	 * @return TBT_Rewards_Helper_Rule_Type
	 */
	public function _getTypeHelper() {
		return Mage::helper ( 'rewards/rule_type' );
	}
	
	protected function _prepareCollection() {
		$catalogruleActionsSingleton = Mage::getSingleton ( 'rewards/catalogrule_actions' );
		$allowedActions = array ();
		if ($this->_getTypeHelper ()->isDistribution ( $this->getRuleTypeId () )) { // is a distribution
			$allowedActions = $catalogruleActionsSingleton->getDistributionActions ();
		} else {
			$allowedActions = $catalogruleActionsSingleton->getRedemptionActions ();
		}
		$collection = Mage::getModel ( 'catalogrule/rule' )->getResourceCollection ()->addFieldToFilter ( "points_action", array ('IN' => $allowedActions ) );
		$this->setCollection ( $collection );
		return parent::_prepareCollection ();
	}
	
	protected function _prepareColumns() {
		$this->addColumn ( 'rule_id', array ('header' => Mage::helper ( 'catalogrule' )->__ ( 'ID' ), 'align' => 'right', 'width' => '50px', 'index' => 'rule_id' ) );
		
		$this->addColumn ( 'name', array ('header' => Mage::helper ( 'catalogrule' )->__ ( 'Rule Name' ), 'align' => 'left', 'index' => 'name' ) );
		
		$this->addColumn ( 'from_date', array ('header' => Mage::helper ( 'catalogrule' )->__ ( 'Date Start' ), 'align' => 'left', 'width' => '120px', 'type' => 'date', 'index' => 'from_date' ) );
		
		$this->addColumn ( 'to_date', array ('header' => Mage::helper ( 'catalogrule' )->__ ( 'Date Expire' ), 'align' => 'left', 'width' => '120px', 'type' => 'date', 'default' => '--', 'index' => 'to_date' ) );
		
		$this->addColumn ( 'is_active', array ('header' => Mage::helper ( 'catalogrule' )->__ ( 'Status' ), 'align' => 'left', 'width' => '80px', 'index' => 'is_active', 'type' => 'options', 'options' => array (1 => 'Active', 0 => 'Inactive' ) ) );
		
		return parent::_prepareColumns ();
	}
	
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array ('id' => $row->getRuleId (), 'type' => $this->getRuleTypeId () ) );
	}

}
