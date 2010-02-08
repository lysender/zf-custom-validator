<?php

/**
 * Filters out and extracts the coordinate keys
 * 
 * @author lysender
 *
 */
class Fms_Filter_Coord implements Zend_Filter_Interface
{
	/**
	 * List of keys to filter out from the parameters
	 * 
	 * @var array
	 */
	protected $_coordKeys = array('x_pos', 'y_pos');
	
	/**
	 * Initializes coordKeys if given
	 * 
	 * @param array $coordKeys
	 * @return void
	 */
	public function __construct(array $coordKeys = null)
	{
		if ($coordKeys !== null)
		{
			$this->_coordKeys = $coordKeys;
		}
	}
	
	/**
	 * Filters out and extract the coordinate keys
	 * for a given array of parameters
	 * 
	 * (non-PHPdoc)
	 * @see Filter/Zend_Filter_Interface#filter($value)
	 * @return array $coord | null
	 */
	public function filter($value)
	{
		if (is_array($value) && !empty($value))
		{
			$ret = array();
			foreach ($this->_coordKeys as $key)
			{
				if (isset($value[$key]))
				{
					$ret[$key] = (int)$value[$key];
				}
			}
			
			if (count($this->_coordKeys) == count($ret))
			{
				return $ret;
			}
		}
		
		return null;
	}
}