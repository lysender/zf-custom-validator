<?php

/**
 * Number validator
 *
 * @author lysender
 */
 
class Dc_Validate_Num extends Zend_Validate_Abstract
{
    const NOT_NUMERIC  	= 'numNotNumeric';
    const TOO_SMALL		= 'numTooSmall';
    const TOO_LARGE 	= 'numTooLarge';
    
    protected $_notNumeric = self::NOT_NUMERIC;
    protected $_tooSmall = self::TOO_SMALL;
    protected $_tooLarge = self::TOO_LARGE;
    
    /**
     * Array key for the num value
     * @var string
     */
    protected $_key = 'num';
    
    /**
     * @var string
     */
    protected $_missingKeyMessage = 'Validating num failed because of missing num key';
    
    /**
     * @var int
     */
    protected $_min = 1;
    
    /**
     * @var int
     */
    protected $_max = 99999;
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_NUMERIC   => "Must be a number",
        self::TOO_SMALL		=> "Number must be between %min% and %max%",
        self::TOO_LARGE		=> "Number must be between %min% and %max%"
    );

    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $_messageVariables = array(
        'min' => '_min',
        'max' => '_max'
    );
    
    /**
     * Sets default option values for this instance
     *
     * @param array $options
     * 
     * @return void
     */
    public function __construct(array $options = array())
    {
		foreach ($options as $opt => $val)
		{
			$key = '_' . $opt;
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
		
		if ($this->_min > $this->_max)
		{
			throw new Zend_Validate_Exception('Minimum must not be greater than maximum.');
		}
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value's num value is a valid number
     * $value must be an associative array containing num key
     *
     * @param  $value
     * @return boolean
     */
    public function isValid($value)
    {
    	if (empty($this->_key))
    	{
    		throw new Exception($this->_missingKeyMessage);
    	}
    
    	if (!is_array($value) || !isset($value[$this->_key]))
    	{
    		$this->_error($this->_missing);
    		return false;
    	}
    	
    	$this->_setValue($value[$this->_key]);

    	if (!is_numeric($value[$this->_key]))
    	{
    		$this->_error($this->_notNumeric);
    		return false;
    	}
    	
    	if ($value[$this->_key] < $this->_min)
    	{
    		$this->_error($this->_tooSmall);
    		return false;
    	}
    	
    	if ($value[$this->_key] > $this->_max)
    	{
    		$this->_error($this->_tooLarge);
    		return false;
    	}

        return true;
    }
}