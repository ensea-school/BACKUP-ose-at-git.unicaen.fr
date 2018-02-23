<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\StructureService;
use UnicaenApp\Util;

$d = "'M','SCOUARNEC ','Jean-Max','EPS',04/04/1963,'France'
'M','BERTHELEM ','Eric','EPS',26/10/1956,'France'
'M','LEVASSEUR','Pierre-Yves','EPS',14/07/1985,'France'
'MME','LEMEE ','Sabine','EPS',18/09/1977,'France'
'M','BIDEL','Arnaud','EPS',21/11/1979,'France'
'M','HENRY','Jean-Luc','EPS',27/06/1977,'France'
'M','AUSSANT','Jean','EPS',06/12/1970,'France'
'M','PHILIPPE','Ludovic','EPS',27/05/1977,'France'
'M','ANDRE ','Vivien','EPS',17/04/1986,'France'
'M','JEANNE ','Pierre','EPS',15/10/1974,'France'
'MME','LETEINTURIER ','Caroline','Lettres',26/11/1976,'France'
'MME','FERNANDES ','Florinda','LVE Anglais',23/01/1974,'France'
'M','VAUDEVIRE ','Stéphane','LVE Anglais',05/02/1972,'France'
'M','ROUQUIER ','Philippe','LVE Anglais',10/06/1976,'France'
'MME','BOUHACEIN ','Caroline','LVE Espagnol',27/10/1973,'France'
'M','PINSAULT ','Nicolas','LVE Espagnol',13/03/1970,'France'
'M','BONINI ','Jean-François','LVE Italien',29/12/1956,'Maroc'
'MME','CARTHY','Karthleen','LVE Italien',12/11/1974,'France'
'M','LABBATE ','Ettore','LVE Italien',11/07/1973,'Italie'
'MME','DESCAMPS ','Sarah','LVE Allemand',02/02/1966,'France'
'MME','LANERY ','Hélène','Mathématiques',30/11/1972,'France'
'M','LEFEUVRE ','Yann','Mathématiques',04/07/1972,'France'
'MME','BOBLIN ','Emmanuele','Mathématiques',28/09/1959,'France'
'MME','WEIBEL','Nathalie','Mathématiques',09/04/1973,'France'
'MME','RODRIGUES DE OLIVEIR','Lisa','Philosophie',21/06/1973,'France'
'MME','MACHEFERT ','Hélène','Philosophie',15/05/1972,'France'
'MME','ARNOUX ','Frédérique','Physique chimie',09/03/1972,'France'
'MME','CHARMARTY ','Sandrine','Physique chimie',07/11/1973,'France'
'M','ROUX ','Giovanni','Physique chimie',10/04/1978,'France'
'MME','SEIGLE ','Mélanie','Physique chimie',20/05/1971,'France'
'M','ORLANDI','Daniel','SES',02/11/1970,'Italie'
'M','ANDRE ','Cédric','SES',01/07/1972,'France'
'M','MOLINA ','Stéphane','SES',05/04/1959,'France'
'M','COUTABLE ','Grégory','STI',25/09/1972,'France'
'M','JOUAUX ','François','STI',05/11/1984,'France'
'M','CAILMAIL ','Philippe','STI',16/04/1972,'France'
'M','CHARDON','Jean-Max','Champs pro',27/03/1963,'France'
'MME','DATHEE-BEAUGE','Anne-Gaelle','Champs pro',15/03/1976,'France'
'MME','LEFORT ','Marie','Champs pro',17/06/1961,'France'
'MME','LEROY ','Christine','Champs pro',30/01/1962,'France'
'MME','PICARD ','Dominique','Champs pro',21/10/1964,'France'
'MME','SEPARI ','Guilaine','Champs pro',04/08/1966,'France'
'M','GILLES ','Arnaud','SVT',27/11/1981,'France'
'M','PITT ','Grégoire','SVT',23/09/1986,'France'
'M','RIBOT ','Nicolas','SVT',08/07/1975,'France'
'MME','DUVIVIER-FAUCHET','Delphine','SVT',03/06/1973,'France'
'MME','HENRY ','Catherine','CPE',04/02/1958,'France'";

$d = explode("\n", $d);

$index = 1;
echo '<pre>';
foreach ($d as $l) {
    $l             = explode(',', $l);
    $civilite      = trim($l[0]) == "'M'" ? 2 : 1;
    $nom           = trim(substr(trim($l[1]),1,-1));
    $prenom        = trim(substr(trim($l[2]),1,-1));
    $dateNaissance = $l[4];
    $paysNaissance = substr(trim($l[5]),1,-1);
    $code = '999999'.str_pad((string)($index++), 2, '0', STR_PAD_LEFT);

    $critere = str_replace( '_', ' ', Util::reduce($nom.' '.$prenom));


    $paysCorresp = [
        'France' => '456',
        'Maroc' => '333',
        'Italie' => '452',
    ];
    if (isset($paysCorresp[$paysNaissance])) $paysNaissance = $paysCorresp[$paysNaissance];

    $sql = "
INSERT INTO intervenant (
  id,
  civilite_id,
  nom_usuel,
  prenom,
  nom_patronymique,
  date_naissance,
  statut_id,
  structure_id,
  source_id,
  source_code,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id,
  annee_id,
  critere_recherche,
  code,
  supann_emp_id,
  pays_naissance_id
) VALUES (
  INTERVENANT_ID_SEQ.NEXTVAL,-- id,
  $civilite,-- civilite_id,
  '$nom',-- nom_usuel,
  '$prenom',-- prenom,
  '$nom',-- nom_patronymique,
  to_date('$dateNaissance', 'dd/mm/YYYY'),-- date_naissance,
  31,-- statut_id,
  102,-- structure_id,
  2,-- source_id,
  '$code',-- source_code,
  sysdate,-- histo_creation,
  4,-- histo_createur_id,
  sysdate,-- histo_modification,
  4,-- histo_modificateur_id,
  2017,-- annee_id,
  '$critere',-- critere_recherche,
  '$code', -- code,
  '$code', -- supann_emp_id,
  $paysNaissance -- pays_naissance_id
);
    ";

    echo $sql;
}
echo '</pre>';