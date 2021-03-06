<?php
/*
 *
 */
namespace FishPig\WordPress\Block\Sidebar\Widget;

/* Parent */
use FishPig\WordPress\Block\AbstractBlock;

abstract class AbstractWidget extends AbstractBlock
{
	/*
	 * Flag used to determine whether to fix option keys
	 *
	 * @var bool
	 */
	protected $_fixOptionKeys = false;
    
	/*
	 * Retrieve the default title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		if (($title = $this->_getData('title')) !== false) {
			return $title ? $title : $this->getDefaultTitle();
		}
		
		return false;
	}

	
	/*
	 *
	 */
	public function getDefaultTitle()
	{
		return '';
	}
	/*
	 * Attempt to load the widget information from the WordPress options table
	 *
	 * @return Fishpig_Wordpress_Block_Sidebar_Widget_Abstract
	 */
	protected function _beforeToHtml()
	{
		if ($this->getWidgetType()) {
			$data = $this->optionManager->getOption('widget_' . $this->getWidgetType());

			if ($data) {
				$data = unserialize($data);
				
				if (isset($data[$this->getWidgetIndex()])) {
					foreach($data[$this->getWidgetIndex()] as $field => $value) {
					
						if ($this->_fixOptionKeys) {
							$field = preg_replace('/([A-Z]{1})([A-Z]{1,})/e', "'$1' . strtolower('$2');", $field);
							$field = preg_replace('/([A-Z]{1})/e', "'_' . strtolower('$1');", $field);
						}
						
						$this->setData($field, $value);
					}
				}
			}
		}

		return parent::_beforeToHtml();
	}
	
	/*
	 * Set some default values
	 *
	 * @param array $defaults
	 * @return $this
	 */
	protected function _setDataDefaults(array $defaults)
	{
		foreach($defaults as $key => $value) {
			if (!$this->hasData($key)) {
				$this->setData($key, $value);
			}
		}
		
		return $this;
	}
	
	/*
	 * Convert data values to something else
	 *
	 * @param array $values
	 * @return $this
	 */
	protected function _convertDataValues(array $values)
	{
		foreach($this->getData() as $key => $value) {
			foreach($values as $find => $replace) {
				if ($value === $find) {
					$this->setData($key, $replace);
					continue;
				}
			}
		}
		
		return $this;
	}	
	
	/*
	 * Retrieve the current page title
	 *
	 * @return string
	 */
	protected function _getPageTitle()
	{
		if (($headBlock = $this->getLayout()->getBlock('head')) !== false) {
			return $headBlock->getTitle();
		}
	
		return $this->_getWpOption('name');
	}

	/*	
	 * Retrieve the meta description for the page
	 *
	 * @return string
	 */
	protected function _getPageDescription()
	{
		if (($headBlock = $this->getLayout()->getBlock('head')) !== false) {
			return $headBlock->getDescription();
		}
	}
	
	/*
	 * Retrieve an ID to be used for the list
	 *
	 * @return string
	 */
	public function getListId()
	{
		if (!$this->hasListId()) {
			$hash = 'wp-' . md5(rand(1111, 9999) . $this->getTitle() . $this->getWidgetType());
			
			$this->setListId(substr($hash, 0, 6));
		}
		
		return $this->_getData('list_id');
	}
}
