<?php 

/**
 * Validator suite for forms and other validation
 * concerns
 * 
 * @author lysender
 */
class Dc_Validate
{
   /**
     * Validator chain, each validator contains the instance,
     * the key for the field to be valdiated and the 
     * breakChainOnFailure flag
     *
     * @var array
     */
    protected $_validators = array();

    /**
     * Array of validation failure messages
     * Each message contains the key of the field validated
     * and the list of messages
     *
     * @var array
     */
    protected $_messages = array();

    /**
     * Adds a validator to the end of the chain
     *
     * If $breakChainOnFailure is true, then if the validator fails, the next validator in the chain,
     * if one exists, will not be executed.
     *
     * @param  string                  $field
     * @param  Zend_Validate_Interface $validator
     * @param  boolean                 $breakChainOnFailure
     * @return Dc_Validate Provides a fluent interface
     */
    public function addValidator($field, Zend_Validate_Interface $validator, $breakChainOnFailure = false)
    {
        $this->_validators[] = array(
        	'field' => $field,
            'instance' => $validator,
            'breakChainOnFailure' => (boolean) $breakChainOnFailure
            );
        return $this;
    }

    /**
     * Returns true if and only if $value passes all validations in the chain
     *
     * Validators are run in the order in which they were added to the chain (FIFO).
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_messages = array();
        $result = true;
        foreach ($this->_validators as $element)
        {
            $validator = $element['instance'];
            if ($validator->isValid($value))
			{
                continue;
            }
            $result = false;
            $messages = array('field' => $element['field'], 'messages' => $validator->getMessages());
            $this->_messages[] = $messages;
            if ($element['breakChainOnFailure'])
			{
                break;
            }
        }
        return $result;
    }

    /**
     * Returns array of validation failure messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }
    
    /**
     * Returns true if and only if there are messages
     * on the validator
     * 
     * @return boolean
     */
    public function hasMessages()
    {
    	return !empty($this->_messages);
    }
    
    /**
     * Merges messages into a single dimensional array or
     * messages. The messages must be compatible with 
     * Dc_Validate for it to work
     * 
     * @param array $messages
     * @return array
     */
    public static function mergeMessages(array $messages)
    {
    	if (!empty($messages))
    	{
    		$result = array();
    		foreach ($messages as $msg)
    		{
    			foreach ($msg['messages'] as $fieldMessage)
    			{
    				$result[] = $fieldMessage;
    			}
    		}
    		return $result;
    	}
    	return false;
    }
    
    /**
     * Returns an array containing the first message and the
     * field name where the message came from
     * 
     * @param array $messages
     * @return array
     */
    public static function getFirstMessage(array $messages)
    {
    	if (!empty($messages))
    	{
    		$result = array();
    		$firstMessage = reset($messages);
    		
    		if (!isset($firstMessage['field']) || !isset($firstMessage['messages']))
    		{
    			return false;
    		}
    		$result['field'] = $firstMessage['field'];
    		
    		$innerMessage = reset($firstMessage['messages']);
    		if (empty($innerMessage))
    		{
    			return false;
    		}
    		
    		$result['message'] = $innerMessage;
    		
    		return $result;
    	}
    	return false;
    }
}