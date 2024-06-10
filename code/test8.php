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

$signature = new \UnicaenSignature\Entity\Db\Signature();
$signature->setLabel("Test de signature");
$signature->setDocumentPath(__DIR__ . '../data/signature/contrat_U01_Bridenne_61774.pdf');
$signature->getAllSignToComplete(true);

$em->persist($signature);
$em->flush();

$em->persist($flow);
$em->flush();

$listFlow = $em->getRepository('\UnicaenSignature\Entity\Db\SignatureFlow')->findAll();
foreach ($listFlow as $flow) {
    echo $flow->getLabel() . "<br/>";
}
echo "</hr>";
$listSignatures = $em->getRepository('\UnicaenSignature\Entity\Db\Signature')->findAll();
foreach ($listSignatures as $signature) {
    echo $signature->getLabel() . "<br/>";
}


