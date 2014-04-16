<?php

chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';
//require_once dirname(__DIR__) . '../vender/autoload';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
