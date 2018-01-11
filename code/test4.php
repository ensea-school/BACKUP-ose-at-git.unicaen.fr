<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

//throw new \Exception('test');
use Application\Constants;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\VolumeHoraire;
use Application\Service\WorkflowService;
use Doctrine\ORM\EntityManager;

/** @var EntityManager $em */
$em = $sl->get(Constants::BDD);

/** @var VolumeHoraire $vh */
$vh = $em->getRepository(VolumeHoraire::class)->find(30176);

$em->beginTransaction();


    $em->beginTransaction();
    $vh->setHeures(26);
    $em->flush($vh);
    $em->commit();
    var_dump($em->getConnection()->fetchAll('select heures from volume_horaire where id = 30176'));


$em->rollback();

var_dump($em->getConnection()->fetchAll('select heures from volume_horaire where id = 30176'));