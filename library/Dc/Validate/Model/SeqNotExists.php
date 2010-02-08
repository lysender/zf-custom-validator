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
 
/**
 * Validates that the code,seq pair does not exists on the database
 * 
 * @author Leonel
 */
class Fms_Validate_Model_SeqNotExists extends Fms_Validate_Model_Abstract
{
	const ERROR_NO_RECORD_FOUND 	= 'modelCodeNoRecordFound';
	const ERROR_RECORD_FOUND 		= 'modelCodeRecordFound';
	
	protected $_noRecordFound;
	protected $_recordFound;
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "Sequence not found in %category%",
        self::ERROR_RECORD_FOUND 	=> "Sequence already exists in %category%"
    );

    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $_messageVariables = array(
        'category' => '_category'
    );
    
    /**
     * @var string
     */
    protected $_model = 'Default_Model_Code';
    
    /**
     * @var string
     */
    protected $_field = array('code', 'seq');
    
    /**
     * @var string
     */
    protected $_category;
    
    /**
     * The expected result for the query
     * This value is used to decide if the validation fails or passed
     * 
     * @var boolean
     */
    protected $_expectedResult = false;
    
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
    protected $_missingKeyMessage = 'Validating code sequence failed because of missing code sequence key';
    
    /**
     * Configurations for the validator $options with the following keys
     * 
     * model is either a string or and instance of Default_Model_Abstract
     * field is either a string or an array of field names
     * exclude optional can either be a String containing a where clause, or an array with `field` and `value` keys
     * category is a string containing the current category for this sequence
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
    	if (isset($options['category']))
    	{
    		$this->_category = $options['category'];
    	}
        
        $this->_expectedResultFailure = self::ERROR_RECORD_FOUND;
    }
    
    /**
     * Querys the record from the database
     * using the provided model
     * 
     * @param $value
     */
    protected function _query($value)
    {
    	$fields = $this->_field;
    	if (!is_array($this->_field))
    	{
    		throw new Zend_Validate_Exception('Fields for validating code must be code and sequence');
    	}
    	$code = $value[$this->_field[0]];
    	$seq = $value[$this->_field[1]];
    	
    	if (!$this->_model instanceof Default_Model_Abstract && is_string($this->_model))
    	{
    		$this->setModel($this->_model);
    	}
    	$result = $this->_model->get($code, $seq, $this->_exclude);
    	return (boolean)$result;
    }
}