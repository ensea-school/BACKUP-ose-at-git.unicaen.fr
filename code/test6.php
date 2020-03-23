<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Application\Service\IntervenantService $si */
$si = $container->get(\Application\Service\IntervenantService::class);


$is = $si->getEntityManager()->getConnection()->fetchAll('
SELECT 
  ID, ANNEE_ID, NOM_USUEL, PRENOM, NUMERO_INSEE, NUMERO_INSEE_CLE, DATE_NAISSANCE, CIVILITE_ID, PAYS_NAISSANCE_ID, DEP_NAISSANCE_ID , NUMERO_INSEE_PROVISOIRE
FROM 
  INTERVENANT 
WHERE 
  NUMERO_INSEE IS NOT NULL 
  AND HISTO_DESTRUCTION IS NULL 
--AND ID = 6391
');


$iv = new \Application\Validator\NumeroINSEEValidator(['provisoire' => false]);
$ov = new \Application\Validator\NumeroINSEEValidatorOld(['provisoire' => false]);

echo '<table class="table table-bordered">';
foreach ($is as $i) {
    $dn      = substr($i['DATE_NAISSANCE'], 0, 10);
    $dn      = \DateTime::createFromFormat('Y-m-d', $dn);
    $dn      = $dn->format(\Application\Constants::DATE_FORMAT);
    $context = [
        'civilite'             => $i['CIVILITE_ID'],
        'dateNaissance'        => $dn,
        'paysNaissance'        => $i['PAYS_NAISSANCE_ID'],
        'departementNaissance' => $i['DEP_NAISSANCE_ID'],
    ];
    $insee   = $i['NUMERO_INSEE'] . $i['NUMERO_INSEE_CLE'];
    $valid   = $iv->isValid($insee, $context);

    $ovValid = $ov->isValid($insee, $context);

    if ($valid != $ovValid) {
        echo '<tr>
<th>' . $i['ID'] . '</th>
<th>' . $i['ANNEE_ID'] . '</th>
<th>' . $i['NOM_USUEL'] . '</th>
<th>' . $i['PRENOM'] . '</th>
<td>' . ($i['NUMERO_INSEE_PROVISOIRE'] ? 'oui' : 'non') . '</td>
<td>' . $insee . '</td>
<td>' . ($valid ? 'oui' : 'non') . '</td>
<td>' . ($ovValid ? 'oui' : 'non') . '</td>
</tr>';
    }
}
echo '</table>';