<?php

/* Chargement de la config globale */

require_once dirname(__DIR__).'/config/application.config.php';

Application::init();
Application::run();