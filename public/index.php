<?php

/* Chargement de la config globale */

ini_set('session.cookie_samesite', 'Strict');

require_once dirname(__DIR__).'/config/application.config.php';

Application::init();
Application::run();