<?php 

/**
 * Validator for given name
 * 
 * @author lysender
 */

class Dc_Validate_Name extends Dc_Validate_String
{
    const INVALID   = 'nameInvalid';
    const TOO_SHORT = 'nameTooShort';
    const TOO_LONG  = 'nameTooLong';
    
	protected $_invalid = self::INVALID;
	protected $_tooShort = self::TOO_SHORT;
	protected $_tooLong = self::TOO_LONG;
	
    /**
     * @var string
     */
    protected $_key = 'name';
    
    /**
     * Message displayed when the key is missing from the class
     * $_key value
     * 
     * @var string
     */
    protected $_missingKeyMessage = 'Validating name failed because of missing name key';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID   => "Name must be a string",
        self::TOO_SHORT => "Name must be between %min% to %max% character(s) long",
        self::TOO_LONG  => "Name must be between %min% to %max% character(s) long"
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
    protected $_min = 1;

    /**
     * Maximum length
     *
     * @var integer
     */
    protected $_max = 60;
}