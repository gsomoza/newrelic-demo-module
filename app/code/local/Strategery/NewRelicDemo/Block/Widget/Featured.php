<?php
/**
 * This Widget Block can be rendered in two modes: SLOW and FAST.
 *
 * The SLOW mode illustrates what happens when you load products within a loop, unfortunately a pretty common mistake
 * in Magento (mostly from unexperienced programmers).
 *
 * The FAST mode illustrates a better way of achieving the same goal with small changes. It does not intend to be the
 * BEST way though as its possible to achieve even better performance with other "less known" approaches.
 *
 * RecentProducts.php
 * @author      Gabriel Somoza <gabriel@usestrategery.com>
 * @date        8/13/2014 4:09 PM
 * @copyright   Copyright Strategery (c) 2014
 */

class Strategery_NewRelicDemo_Block_Widget_Featured
	extends Mage_Core_Block_Abstract
	implements Mage_Widget_Block_Interface {

	const MODE_SLOW = 'slow';
	const MODE_FAST = 'fast';

	/** @var Mage_Catalog_Model_Resource_Product_Collection */
	protected $_collection;

	/**
	 * Prepares the collection of products that are marked as "featured"
	 */
	protected function _getCollection() {
		$method = $this->_getRenderMode();
		$collection = Mage::getResourceSingleton( 'catalog/product_collection');
		if ( $method == self::MODE_SLOW ) {
			//slow version
			$collection->addAttributeToFilter( 'featured', array('eq' => 1) );
		} else {
			// optimized version
			$collection->clear()
				->addAttributeToFilter('featured', array('eq' => 1))
				->addAttributeToSelect('name')
				->addUrlRewrite();
		}
		return $collection;
	}

	/**
	 * Produces the list of links
	 * @return string
	 */
	protected function _toHtml() {
		$start = microtime(true);
		$collection = $this->_getCollection();
		$count = $collection->count();
		$method = $this->_getRenderMode();
		$html = "<p>Number of Featured Products Found: $count</p>";
		$html .= "<p>Render Mode: $method</p>";
		$html .= '<p>Select Query: </p><pre style="width:100%;overflow:auto">' . $collection->getSelect() . '</pre><p>&nbsp;</p>';
		$html .= '<ul style="float:left">';
		foreach($collection as $product) {
			/** @var Mage_Catalog_Model_Product $product */
			if($this->_getRenderMode() == self::MODE_SLOW) {
				/* NOTE: Doing this is a big mistake! The developer had to load each product *individually* because
					Magento won't pull the product name from the database and provide URL rewrite capabilities through
					the Product model *unless* the collection was configured correctly before loading */
				$product = Mage::getModel('catalog/product')->load($product->getId());
			}
			$html .= '<li>';
			$url = $product->getUrlPath();
			$name = $product->getName();
			$html .= "<a href=\"$url\">$name</a>";
			$html .= '</li>';
		}
		$html .= '</ul>';
		$elapsed = microtime(true) - $start;
		$html .= '<div style="float:right;width:250px;padding:10px;background:lightgrey;">Time Elapsed (sec): ' . $elapsed . '</div>';
		return $html;
	}

	/**
	 * @return string "slow" | "fast"
	 */
	protected function _getRenderMode() {
		return $this->getData('render_mode');
	}

} 
