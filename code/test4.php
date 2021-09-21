<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$test4 = new Test4($controller, $container);
$test4->run();





class Test4
{
    /**
     * @var \Zend\Mvc\Controller\AbstractController
     */
    public $controller;

    /**
     * @var Interop\Container\ContainerInterface
     */
    public $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;



    /**
     * @param $controller
     * @param $container
     */
    public function __construct($controller, $container)
    {
        $this->controller = $controller;
        $this->container  = $container;
        $this->em         = $this->container->get(\Application\Constants::BDD);
    }



    /**
     * @var \Plafond\Service\PlafondService
     */
    public $sp;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    public $tvh;



    public function run()
    {
        $this->sp = $this->container->get(\Plafond\Service\PlafondService::class);

        $intervenantId = 58753;
        $elementId     = 160230;
        $structureId   = 594;
        $tvhId         = 1;

        $this->tvh = $this->em->find(\Application\Entity\Db\TypeVolumeHoraire::class, $tvhId);

        $intervenant = $this->em->find(\Application\Entity\Db\Intervenant::class, $intervenantId);
        $element     = $this->em->find(\Application\Entity\Db\ElementPedagogique::class, $elementId);
        $structure   = $this->em->find(\Application\Entity\Db\Structure::class, $structureId);
        $vh          = $this->em->find(\Application\Entity\Db\VolumeHoraire::class, 414168);
        $fr          = $this->em->find(\Application\Entity\Db\FonctionReferentiel::class, 151);

        $this->aff($intervenant);
        $this->aff($element);
        $this->aff($structure);
        $this->aff($vh);
        $this->aff($fr);
    }



    private function aff($entity)
    {
        echo '<h1>' . get_class($entity) . '</h1>';

        echo '<h2>Tous les contrôles</h2>';
        $r = $this->sp->controle($this->tvh, $entity, false);
        var_dump($r);

        echo '<h2>Uniquement s\'il y a blocage</h2>';
        $r = $this->sp->controle($this->tvh, $entity, true);
        var_dump($r);
    }
}