<?php
/**
 * General Bootrsapping Class
 * @author Leonel
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{	
	/**
	 * Initialize Zend Autoloading
	 * 
	 */

    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default',
            'basePath'  => APPLICATION_PATH,
        ));
        return $autoloader;
    }
    
    /**
     * Autoloads project library
     */
    protected function _initFmsAutoloader()
    {
    	$autoloader = Zend_Loader_Autoloader::getInstance();
    	$autoloader->registerNamespace('Fms_');
    }
	
    /**
     * Autoloads project library
     */
    protected function _initDcAutoloader()
    {
    	$autoloader = Zend_Loader_Autoloader::getInstance();
    	$autoloader->registerNamespace('Dc_');
    }
    
    /**
     * Initialized MVC and Layouts
     * 
     */
    
    protected function _initPresentation()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->setHttpEquiv('Content-Type', 'text/html; charset=utf-8');
		$view->headTitle("FMS | ");
    }
    
    /**
     * Initialized Helpers
     * 
     */
    protected function _initActionHelpers()
    {
    	Zend_Controller_Action_HelperBroker::addPath(
    		APPLICATION_PATH . "/controllers/helpers"
    	);
    }
    
    /**
     * Initialized Routes
     * No routes as of this moment
     */
    protected function _initRoutes()
    {
    	//$config = new Zend_Config_Ini (
    		//APPLICATION_PATH . '/configs/routes.ini', 'production'
    	//);
    	//$frontController = Zend_Controller_Front::getInstance();
    	//$router = $frontController->getRouter();
    	//$router->addConfig( $config, 'routes' );
    }
    
    /**
     * Setup Caching for Table Metadata
     * 
     */
    protected function _initMetadataCache()
    {
		//Stores within 24 hours
		$frontendOptions = array( 
		                    'lifetime'                  => 86400, 
		                    'automatic_serialization'   => true,
							'host'						=> 'localhost',
							'port'						=> 11211
		                    ); 
		$backendOptions  = array( 
		                     'cache_dir'                => APPLICATION_PATH . '/tmp' 
		                    ); 
		$cache = Zend_Cache::factory( 
		                    'Core', 
		                    'File', 
		                    $frontendOptions, 
		                    $backendOptions 
		                    );
		//Cache table metadata
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }
    
    /**
     * Setup General Caching
     * 
     */
    protected function _initCache()
    {
		//General caching (Stores within 30 minutes)
		$frontendOptions = array( 
		                    'lifetime'                  => 18000, 
		                    'automatic_serialization'   => true,
							'host'						=> 'localhost',
							'port'						=> 11211
		                    ); 
		$backendOptions  = array( 
		                    'cache_dir'                => APPLICATION_PATH . '/tmp' 
		                    ); 
		$cache = Zend_Cache::factory( 
		                    'Core', 
		                    'File', 
		                    $frontendOptions, 
		                    $backendOptions 
		                    ); 
		//save to registry
		$registry = Zend_Registry::getInstance();
		$registry->set('cache', $cache);
    }
    
    /**
     * Initializes the plugin loader cache
     * 
     */
    protected function _initPluginLoaderCache()
    {
		$classFileIncCache = APPLICATION_PATH .  '/data/pluginLoaderCache.php'; 
		if (file_exists($classFileIncCache))
		{ 
		    include_once $classFileIncCache; 
		} 
		Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache); 
    }
    
    //protected function _initLogger()
    //{
    	//Techtuit_Log::initLogger();
    //}
    
    /**
     * Initializes Messages and Web Config
     * 
     */
    protected function _initMessages()
    {
    	
    	//messages
		//$messages = new Zend_Config_Ini(
		    //APPLICATION_PATH . '/configs/techtuit_messages.ini', 
		    //APPLICATION_ENV
		//);

		//web config
		$config = new Zend_Config_Ini(
		    APPLICATION_PATH . '/configs/application.ini', 
		    APPLICATION_ENV
		);
		
		//save to registry
		$registry = Zend_Registry::getInstance();
		//$registry->set('messages', $messages);
		$registry->set('config', $config);
		unset($config);
    }
    
    protected function _initSession()
    {
		Zend_Locale::setDefault('en_US');
		Zend_Session::start();
    }
    
}//end class