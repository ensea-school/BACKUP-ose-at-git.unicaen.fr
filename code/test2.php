<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$controller->flashMessenger()->addSuccessMessage('addSuccessMessage');
$controller->flashMessenger()->addInfoMessage('addInfoMessage');
$controller->flashMessenger()->addWarningMessage('addWarningMessage');
$controller->flashMessenger()->addErrorMessage('addErrorMessage');

$messenger = $this->messenger();

$messenger->addCurrentMessagesFromFlashMessengerWithNamespace('error');

echo $messenger;

?>
