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
 * @author		Leonel Baer - Jan 11, 2010
 * @copyright	Copyright (c) 2009 Esperant System Philippines Corp (http://esp.ph)
 * @desc			
 */
 
class Fms_Auth_Session extends Fms_Auth
{	
	/**
	 * Returns a variable from the session
	 * 
	 * @param $key
	 * @return mixed
	 */
	public static function getVar($key)
	{
		$session = self::getSession();
		return $session->$key;
	}
	
	/**
	 * Sets a variable into the session
	 * 
	 * @param $key
	 * @param $value
	 * 
	 * @return void
	 */
	public static function setVar($key, $value)
	{
		$session = self::getSession();
		$session->$key = $value;
	}
}