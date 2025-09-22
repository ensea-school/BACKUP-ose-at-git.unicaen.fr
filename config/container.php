<?php

use Framework\Application\Application;

Application::getInstance()->init();

require_once('module/Application/src/functions.php');

return Application::getInstance()->container();