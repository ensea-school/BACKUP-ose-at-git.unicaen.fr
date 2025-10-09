<?php

use Unicaen\Framework\Application\Application;

if (!file_exists('config.local.php')) {
    die('Le fichier de configuration config.local.php doit être mis en place et configuré, or il n\'a pas été trouvé.');
}

Application::getInstance()->init();

require_once('module/Application/src/functions.php');

return Application::getInstance()->container();