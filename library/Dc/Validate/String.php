<?php

/**
 * Validation for regular strings
 * 
 * @author lysender
 */
class Dc_Validate_String extends Zend_Validate_Abstract
{
    const INVALID   = 'stringInvalid';
    const TOO_SHORT = 'stringTooShort';
    const TOO_LONG  = 'stringTooLong';

    protected $_invalid = self::INVALID;
    protected $_tooShort = self::TOO_SHORT;
    protected $_tooLong = self::TOO_LONG;
    
    /**
     * @var string
     */
    protected $_key = 'string';
    
    /**
     * Message displayed when the key is missing from the class
     * $_key value
     * 
     * @var string
     */
    protected $_missingKeyMessage = 'Validating string failed because of missing string key';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID   => "Value must be a string",
        self::TOO_SHORT => "Value must be greater than %min% character(s) long",
        self::TOO_LONG  => "Value must be less than %max% character(s) long"
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'min' => '_min',
        'max' => '_max'
    );

    /**
     * Minimum length
     *
     * @var integer
     */
    protected $_min = 0;

    /**
     * Maximum length
     *
     * If null, there is no maximum length
     *
     * @var integer|null
     */
    protected $_max;

    /**
     * Encoding to use
     *
     * @var string|null
     */
    protected $_encoding;

    /**
     * Sets validator options
     * $options is an array the either contains the following keys
     * min: minimum length
     * max: maximum length
     * encoding: encoding to use
     * key: the key of the value to ve validated
     *
     * @param  array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
		foreach ($options as $opt => $val)
		{
			$key = '_' . $opt;
			if (isset($this->$key))
			{
				$func = 'set' . ucfirst($opt);
				$this->$func($val);
			}
		}
    }

    /**
     * Returns the key for the value
     * 
     * @return string
     */
    public function getKey()
    {
    	return $this->_key;
    }
    
    /**
     * Sets the key for the value
     * 
     * @param $key
     * @return this
     */
    public function setKey($key)
    {
    	if (!empty($key) && is_string($key))
    	{
    		$this->_key = $key;
    	}
    	return $this;
    }
    
    /**
     * Returns the min option
     *
     * @return integer
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Sets the min option
     *
     * @param  integer $min
     * @throws Zend_Validate_Exception
     * @return this
     */
    public function setMin($min)
    {
        if (null !== $this->_max && $min > $this->_max)
        {
            throw new Zend_Validate_Exception("The minimum must be less than or equal to the maximum length, but $min >"
                                            . " $this->_max");
        }
        $this->_min = max(0, (integer) $min);
        return $this;
    }

    /**
     * Returns the max option
     *
     * @return integer|null
     */
    public function getMax()
    {
        return $this->_max;
    }

    /**
     * Sets the max option
     *
     * @param  integer|null $max
     * @throws Zend_Validate_Exception
     * @return this
     */
    public function setMax($max)
    {
        if (null === $max)
        {
            $this->_max = null;
        }
        else if ($max < $this->_min)
        {
            throw new Zend_Validate_Exception("The maximum must be greater than or equal to the minimum length, but "
                                            . "$max < $this->_min");
        }
        else
        {
            $this->_max = (integer) $max;
        }

        return $this;
    }

    /**
     * Returns the actual encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Sets a new encoding to use
     *
     * @param string $encoding
     * @return this
     */
    public function setEncoding($encoding = null)
    {
        if ($encoding !== null)
        {
            $orig   = iconv_get_encoding('internal_encoding');
            $result = iconv_set_encoding('internal_encoding', $encoding);
            if (!$result)
            {
                throw new Zend_Validate_Exception('Given encoding not supported on this OS!');
            }

            iconv_set_encoding('internal_encoding', $orig);
        }

        $this->_encoding = $encoding;
        return $this;
    }

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
    	if (!isset($value[$this->_key]))
    	{
    		throw new Zend_Validate_Exception($this->_missingKeyMessage);
    	}
    	
        if (!is_string($value[$this->_key]))
        {
            $this->_error($this->_invalid);
            return false;
        }

        $this->_setValue($value[$this->_key]);
        if ($this->_encoding !== null)
        {
            $length = iconv_strlen($value[$this->_key], $this->_encoding);
        }
        else
        {
            $length = iconv_strlen($value[$this->_key]);
        }

        if ($length < $this->_min)
        {
            $this->_error($this->_tooShort);
        }

        if (null !== $this->_max && $this->_max < $length)
        {
            $this->_error($this->_tooLong);
        }

        if (count($this->_messages))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
