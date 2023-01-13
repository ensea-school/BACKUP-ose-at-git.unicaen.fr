<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */
?>
<div class="vue-app">


    <h2>mission</h2>
    <mission mission="{msg:'test de mission'}"></mission>

    <h2>test</h2>
    <mission-test msg="Et voici mon test!"></mission-test>

    <h2>Hello world</h2>
    <hello-world msg="bonjour le monde de partout!"></hello-world>

    <h2>Missions</h2>
    <mission-missions intervenant="100000" can-add-mission="1"></mission-missions>
</div>