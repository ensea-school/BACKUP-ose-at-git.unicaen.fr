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

/**
 * @var $service \UnicaenSignature\Service\SignatureService
 */

$service = $container->get(\UnicaenSignature\Service\SignatureService::class);

$recipientDatas = [
    'firstname' => 'Le Courtes',
    'lastname'  => 'Antony',
    'email'     => 'anthony.lecourtes@gmail.com',
    'phone'     => '023241525255',
];
$signature      = $service->createSignature('', \UnicaenSignature\Utils\SignatureConstants::SIGN_VISUAL, $recipientDatas);


var_dump($signature);


