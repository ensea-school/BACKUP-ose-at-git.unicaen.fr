<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$em = $container->get(\Doctrine\ORM\EntityManager::class);



$params = [
    'contrat' => 36821,
    'volumeHoraireIndex' => 0
];

$c = $em->getRepository(\Contrat\Entity\Db\TblContrat::class)->findOneBy($params);

echo $c->getId();
/** @var \Contrat\Entity\Db\TblContratVolumeHoraire[] $vhs */
$vhs = $c->getVolumesHoraires();

foreach( $vhs as $vh ){
    echo "<h1>VH</h1>";
    var_dump($vh->getService()->getElementPedagogique()->getLibelle());
}

