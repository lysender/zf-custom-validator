<?php

/**
 * Number validator
 *
 * @author lysender
 */
 
class Dc_Validate_OrderCount extends Dc_Validate_Num
{
    const NOT_NUMERIC  	= 'orderCountNotNumeric';
    const TOO_SMALL		= 'orderCountTooSmall';
    const TOO_LARGE 	= 'orderCountTooLarge';
    
    protected $_notNumeric = self::NOT_NUMERIC;
    protected $_tooSmall = self::TOO_SMALL;
    protected $_tooLarge = self::TOO_LARGE;
	
    /**
     * Array key for the num value
     * @var string
     */
    protected $_key = 'order_count';
    
    /**
     * @var string
     */
    protected $_missingKeyMessage = 'Validating order count failed because of missing order count key';
    
    /**
     * @var int
     */
    protected $_min = 0;
    
    /**
     * @var int
     */
    protected $_max = 126;
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_NUMERIC   => "Order count must be a valid number",
        self::TOO_SMALL		=> "Order count must be between %min% and %max%",
        self::TOO_LARGE		=> "Order count must be between %min% and %max%"
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
}