<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Constants;

$em = $sl->get(Constants::BDD);
/* @var $em \Doctrine\ORM\EntityManager */


$etapes = [];
$es     = $em->getRepository(\Application\Entity\Db\WfEtape::class)->findAll();
foreach ($es as $e) {
    $etapes[$e->getId()] = $e;
}

$deps = [];
$d    = $em->getConnection()->fetchAll('SELECT * FROM wf_etape_dep');
foreach ($d as $dep) {
    $s = (int)$dep['ETAPE_SUIV_ID'];
    $p = (int)$dep['ETAPE_PREC_ID'];

    if (!isset($deps[$s])) {
        $deps[$s] = [];
    }
    $deps[$s][] = $p;
}


foreach ($etapes as $etape) {
    renderEtape($etape, $deps, $etapes);
}


function renderEtape(\Application\Entity\Db\WfEtape $etape, $deps, $etapes)
{
    ?>
    <div style="margin-left:3em;border-left:1px #ddd solid">
        <?= $etape->getCode() ?>
        <?php if (isset($deps[$etape->getId()])) {
            foreach ($deps[$etape->getId()] as $dep): ?>
                <?= renderEtape($etapes[$dep], $deps, $etapes) ?>
            <?php endforeach;
        } ?>
    </div>
    <?php
}