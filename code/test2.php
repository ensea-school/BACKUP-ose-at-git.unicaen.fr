<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Interop\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$sql = "
SELECT
  w.annee_id,
  w.intervenant_id,
  si.source_code statut_intervenant,
  s.libelle_court structure,
  w.etape_code,
  w.atteignable,
  w.objectif,
  w.realisation
FROM
  tbl_workflow w
  JOIN wf_etape e ON e.id = w.etape_id
  JOIN statut_intervenant si ON si.id = w.statut_intervenant_id
  LEFT JOIN structure s ON s.id = w.structure_id
ORDER BY
  w.intervenant_id,
  e.ordre
";


/** @var $em \Doctrine\ORM\EntityManager */
$em = $container->get(\Application\Constants::BDD);

$ids = $em->getConnection()->query($sql);
$feuilles = [];

foreach( $ids as $id ){
    $intervenant = (int)$id['INTERVENANT_ID'];
    unset($id['INTERVENANT_ID']);
    $feuilles[$intervenant][] = $id;
}

var_dump($feuilles);