<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get('doctrine.entitymanager.orm_default');


$flow = new \UnicaenSignature\Entity\Db\SignatureFlow();
$flow->setLabel("Signature contrat");
$flow->setDescription("Signature electronique contrat");

/*$em->persist($flow);
$em->flush();*/

var_dump($flow);


