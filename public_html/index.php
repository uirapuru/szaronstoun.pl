<?php

date_default_timezone_set("Europe/Paris");

defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '~/Library'),
realpath(APPLICATION_PATH . '/../../../Library'),
            get_include_path(),
        )));

require_once 'Zend/Loader/Autoloader.php';

$oAutoloader = Zend_Loader_Autoloader::getInstance();
$oAutoloader->registerNamespace('Core_');


require_once 'Zend/Application.php';

$application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
);
try {
    $application->bootstrap()
            ->run();
}
catch (Exception $e) {
    echo '<pre>' . $e . '</pre>';
}
