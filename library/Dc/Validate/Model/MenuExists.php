<?php
 
/**
 * Validates that the table number exists in the database
 * 
 * @author Leonel
 */
class Fms_Validate_Model_MenuExists extends Fms_Validate_Model_Abstract
{
	const ERROR_NO_RECORD_FOUND 	= 'modelMenuNoRecordFound';
	const ERROR_RECORD_FOUND 		= 'modelMenuRecordFound';
	
	protected $_noRecordFound;
	protected $_recordFound;
    
	/**
	 * Cache for menu
	 * @var array
	 */
	protected static $_menu = array();
	
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_NO_RECORD_FOUND => "Menu item not found",
        self::ERROR_RECORD_FOUND 	=> "Menu item already exists"
    );
    
    /**
     * @var string
     */
    protected $_model = 'Default_Model_Code';
    
    /**
     * @var string
     */
    protected $_field = 'order_no';
    
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
    protected $_missingKeyMessage = 'Validating menu item failed because of missing menu item key';
    
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
        
        $this->_expectedResultFailure = self::ERROR_NO_RECORD_FOUND;
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
    	if (!is_string($this->_field))
    	{
    		throw new Zend_Validate_Exception('Field for validating menu item must be a string');
    	}
    	$code = Default_Model_Code::CODE_MENU;
    	$seq = (int)$value[$this->_field];
    	
    	// Use cache if it is not empty
    	if (!empty(self::$_menu))
    	{
    		return isset(self::$_menu[$seq]);
    	}
    	else
    	{
    		// otherwise query the database
	    	if (!$this->_model instanceof Default_Model_Abstract && is_string($this->_model))
	    	{
	    		$this->setModel($this->_model);
	    	}
	    	$result = $this->_model->get($code, $seq, $this->_exclude);
	    	return (boolean)$result;
    	}
    }
    
    /**
     * setCachedMenu()
     * 
     * @param array $data
     * @return void
     */
    public static function setCachedMenu(array $data)
    {
    	if (!empty($data))
    	{
    		$cache = array();
    		foreach ($data as $key => $row)
    		{
    			$cache[$row['seq']] = $row['seq'];
    		}
    		self::$_menu = $cache;
    	}
    }
}