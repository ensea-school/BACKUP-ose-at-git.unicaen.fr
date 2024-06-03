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


$sr = $em->getRepository(UnicaenSignature\Entity\Db\Signature::class);


$data = $sr->findAll();

var_dump($data);