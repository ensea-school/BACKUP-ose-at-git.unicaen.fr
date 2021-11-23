<?php

/* Chargement de la config globale */
require_once dirname(dirname(__DIR__)) . '/config/application.config.php';

Application::init();
Application::run();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(Application::$container);