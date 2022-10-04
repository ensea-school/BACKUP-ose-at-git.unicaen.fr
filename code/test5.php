<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


// Formatage...

$sql = "
SELECT
  nsup.id noeud_sup_id,
  lsup.id lien_sup_id,
  nl.id noeud_liste_id,
  linf.id lien_inf_id,
  ninf.id noeud_inf_id,

  nsup.etape_id etape_id,
  ninf.element_pedagogique_id element_pedagogique_id
FROM
  noeud nsup
  JOIN lien lsup ON lsup.histo_destruction IS NULL AND lsup.noeud_sup_id = nsup.id
  JOIN noeud nl ON nl.histo_destruction IS NULL AND nl.liste = 1 AND nl.id = lsup.noeud_inf_id
  JOIN lien linf ON linf.histo_destruction IS NULL AND linf.noeud_sup_id = nl.id
  JOIN noeud ninf ON ninf.histo_destruction IS NULL AND ninf.id = linf.noeud_inf_id AND ninf.liste = 0
WHERE
  nsup.histo_destruction IS NULL
AND nsup.liste = 0
";

sqlDump($sql);


/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(\Application\Constants::BDD);
$qb = $em->createQuery('SELECT a FROM ' . \Application\Entity\Db\Annee::class . ' a');
sqlDump($qb);


$xml = "<?xml version=\"1.0\"?>
<email>
    <entete>
        <date type=\"JJMMAAAA\">28102003</date>
        <heure type=\"24\" local=\"(GMT+01 :00)\">14:01:01</heure>
        <expediteur>
            <adresse mail=\"marcel@ici.fr\">Marcel</adresse>
        </expediteur>
        <recepteur>
            <adresse mail=\"robert@labas.fr\">Robert</adresse>
        </recepteur>
        <sujet>Hirondelle</sujet>
    </entete>
    <corps>
        <salutation>Salut,</salutation>
        <paragraphe>Pourrais-tu m'indiquer quelle est la vitesse de vol d'une hirondelle
            transportant une noix de coco ?</paragraphe>
        <politesse>A très bientôt,</politesse>
        <signature>Marcel</signature>
    </corps>
</email>";

xmlDump($xml);

$dom = new \DOMDocument;
$dom->loadXml($xml);
xmlDump($dom);