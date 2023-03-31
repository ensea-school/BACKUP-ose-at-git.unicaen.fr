<?php

use Unicaen\OpenDocument\Calc;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$date1 = \DateTime::createFromFormat('Y-m-d H:i:s', '2023-03-30 10:00:00');
$date2 = \DateTime::createFromFormat('Y-m-d H:i:s','2023-03-31 14:30:00');
$interval = $date1->diff($date2);
$minutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;

echo "Il y a $minutes minutes de différences";
