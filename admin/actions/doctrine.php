<?php

/* Chargement de la config globale */
require_once dirname(dirname(__DIR__)).'/config/application.config.php';

Application::init();
Application::run();

/* @var $cli \Symfony\Component\Console\Application */
$cli = Application::$container->get('doctrine.cli');
exit($cli->run());