<?php

class Bootstrap
        extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Konfiguracja
     * @var Zend_Config 
     */
    protected $_config;

    /**
     * Zwraca konfigurację
     * @return Zend_Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    public function __construct($application)
    {
        $this->_config = new Zend_Config($application->getOptions(), true);
        Zend_Registry::set('config', $this->_config);
        Zend_Registry::set('db', $this->_config->resources->db);

        parent::__construct($application);
    }

    public function _initAutoloader()
    {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                    'basePath' => APPLICATION_PATH,
                    'namespace' => '',
                    'resourceTypes' => array(
                        'form' => array('path' => 'forms/', "namespace" => 'Form'),
                        'model' => array('path' => 'models/', "namespace" => 'Model')
                    )
                ));
        
        return $resourceLoader;
    }

}

