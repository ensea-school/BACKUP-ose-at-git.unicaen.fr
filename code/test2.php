<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Connecteur\LdapConnecteur;
use Application\Constants;
use Doctrine\ORM\EntityManager;
use UnicaenApp\Mapper\Ldap\People;

$sql = '
SELECT 
  a.id, 
  p.supann_emp_id
FROM 
  affectation a
  JOIN personnel p ON p.id = a.personnel_id 
WHERE 
  a.histo_destruction IS NULL
';

/** @var EntityManager $em */
$em = $sl->get(Constants::BDD);

/** @var People $ldap */
$ldapMapper = $sl->get('ldap_people_mapper');

/** @var LdapConnecteur $ldap */
$ldap = $sl->get(LdapConnecteur::class);

$a = $em->getConnection()->fetchAll($sql);

foreach( $a as $aff ){
    $p = $ldapMapper->findOneByNoIndividu($aff['SUPANN_EMP_ID'], true);
    $aid = $aff['ID'];
    if ($p){
        $login = $p->getSupannAliasLogin();

        $utilisateur = $ldap->getUtilisateur($login);

        $uid = $utilisateur->getId();
        $usql = "UPDATE affectation SET utilisateur_id=$uid WHERE id = $aid;";
        echo $usql."<br />";
    }
}

/*

select
  a.id,
  p.nom_usuel || ' ' || p.prenom personnel,
  p.supann_emp_id supp,
  r.libelle role,
  s.libelle_court structure,
  s.libelle source,
  a.source_code a_sc,
  p.source_code p_sc
from
  affectation a
  JOIN source s ON s.id = a.source_id
  JOIN personnel p ON p.id = a.personnel_id
  JOIN role r ON r.id = a.role_id
  LEFT JOIN structure s ON s.id = a.structure_id
where
  a.utilisateur_id is null
  and a.histo_destruction is null;

update affectation set utilisateur_id = 1 where id = 1;
delete from affectation where utilisateur_id is null;

*/