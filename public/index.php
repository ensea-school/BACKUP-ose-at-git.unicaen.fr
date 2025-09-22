<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';
require 'config/container.php';

use Framework\Application\Application;

Application::getInstance()->run();