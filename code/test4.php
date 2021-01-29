<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Application\Processus\IntervenantProcessus $ip */
$ip = $container->get(\Application\Processus\IntervenantProcessus::class);
$is = $ip->suppression();

$intervenant = $container->get(\Application\Service\IntervenantService::class)->get(191233);

$data = $is->getData($intervenant);
var_dump($data);

$tree = $is->getTree($intervenant);

affNode($tree);


function affNode(\Application\Model\TreeNode $n)
{
    echo '<div style="margin-left:2em">';
    echo '<span class="' . $n->getIcon() . '"></span> ' . $n->getLabel();
    echo ' <span style="color:#eee">id=' . $n->getId() . '</span>';
    echo '<br />';

    $childs = $n->getChildren();
    foreach ($childs as $c) {
        affNode($c);
    }

    echo '</div>';
}