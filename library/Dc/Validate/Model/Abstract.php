<?php

/**
 * Esperant System Philippines Corp
 *
 * LICENSE
 *
 * All codes that appears on this file is property of
 * Esperant System Philippines Corp
 * All files within this project is also property of ESP.
 * Other third party libraries such as Zend Framework,
 * PEAR, Smarty, Swiftmailer, SimpleTest, PHPUnit and the like
 * are properties of their repective owners.
 * All rights reserved.
 * 
 * Illegal copying, modifications, re-distribution and publishing
 * of the source code without permission from the company
 * is strictly prohibited.
 *
 * @author		Leonel Baer - Jan 12, 2010
 * @copyright	Copyright (c) 2009 Esperant System Philippines Corp (http://esp.ph)
 * @desc			
 */
 
abstract class Fms_Validate_Model_Abstract extends Zend_Validate_Abstract
{
	const ERROR_NO_RECORD_FOUND 	= 'modelNoRecordFound';
	const ERROR_RECORD_FOUND 		= 'modelRecordFound';
	
	protected $_noRecordFound;
	protected $_recordFound;
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "No record found",
        self::ERROR_RECORD_FOUND 	=> "Record already exists"
    );

    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $_messageVariables = array();
    
    /**
     * The expected result for the query
     * This value is used to decide if the validation fails or passed
     * 
     * @var boolean
     */
    protected $_expectedResult = true;
    
    /**
     * The error message when the exptected result failed
     * 
     * @var string
     */
    protected $_expectedResultFailure;
    
    /**
     * Message displayed when the key is missing from the class
     * $_key value
     * 
     * @var string
     */
    protected $_missingKeyMessage = 'Validating field failed because of missing field key';
    
    /**
     * @var Defalt_Model_Abstract
     */
    protected $_model;
    
    /**
     * @var string|array
     */
    protected $_field;
    
    /**
     * @var string|array
     */
    protected $_exclude;
    
    /**
     * Configurations for the validator $options with the following keys
     * 
     * model is either a string or and instance of Default_Model_Abstract
     * field is either a string or an array of field names
     * exclude optional can either be a String containing a where clause, or an array with `field` and `value` keys
     * 
     * @param array $options
     * 
     * @return void
     */
    public function __construct(array $options = array())
    {
    	if (isset($options['model']))
    	{
    		$this->setModel($options['model']);
    	}
    	if (isset($options['field']))
    	{
    		$this->setField($options['field']);
    	}
    	if (isset($options['exclude']))
    	{
        	$this->setExclude($options['exclude']);
    	}
        
        $this->_expectedResultFailure = self::ERROR_RECORD_FOUND;
    }
    
    /**
     * getModel()
     * 
     * @return Default_Model_Abstract
     */
    public function getModel()
    {
    	return $this->_model;
    }
    
    /**
     * Sets the model to be used by this validator
     * 
     * @param string|Default_Model_Abstract $model
     * 
     * @return this
     */
    public function setModel($model)
    {
    	if (is_string($model))
    	{
    		try {
    			$this->_model = new $model;
    		}
    		catch (Zend_Exception $e)
    		{
    			throw new Zend_Validate_Exception("Model $model is not found");
    		}
    	}
    	else if ($model instanceof Default_Model_Abstract)
    	{
    		$this->_model = $model;
    	}
    	else
    	{
    		throw new Zend_Validate_Exception('Model is not set');
    	}
    	
    	return $this;
    }

    /**
     * getField()
     * 
     * @return string|array
     */
    public function getField()
    {
    	return $this->_field;
    }
    
    /**
     * Sets the field to lookup
     * $field is either a string or an array of fields
     * 
     * @param string|array $field
     * @return this
     */
    public function setField($field)
    {
    	if ($field)
    	{
    		$this->_field = $field;
    	}
    	else
    	{
    		throw new Zend_Validate_Exception('Field is empty');
    	}
    	
    	return $this;
    }
    
    /**
     * getExclude()
     * 
     * @return string|array
     */
    public function getExclude()
    {
    	return $this->_exclude;
    }
    
    /**
     * Sets the records to be excluded from the query
     * $exclude is an array of either a key=>value pair or an array of string of where clauses
     * 
     * @param string|array $exclude
     * @return this
     */
    public function setExclude($exclude)
    {
    	if ($exclude)
    	{
    		$this->_exclude = $exclude;
    	}
    	
    	return $this;
    }
    
    /**
     * Runs the model get query
     * Must be implemented on the child class
     * 
     * @param string|array $value
     * @return boolean
     */
    abstract protected function _query($value);
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the string length of $value is at least the min option and
     * no greater than the max option (when the max option is not null).
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
    	if (is_string($this->_field))
    	{
    		if (!isset($value[$this->_field]))
    		{
    			throw new Zend_Validate_Exception($this->_missingKeyMessage);
    		}
    	}
    	else if (is_array($this->_field))
    	{
    		foreach ($this->_field as $f)
    		{
    			if (!isset($value[$f]))
    			{
    				throw new Zend_Validate_Exception("Missing field $f in values");
    			}
    		}
    	}
    	else
    	{
    		throw new Zend_Validate_Exception('No valid field is defined');
    	}
    	
    	$result = $this->_query($value);
    	
    	if ($result === $this->_expectedResult)
    	{
    		return true;
    	}
    	else
    	{
    		$this->_error($this->_expectedResultFailure);
    		return false;
    	}
    }
}