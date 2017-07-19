<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

/** @var \UnicaenTbl\Service\QueryGeneratorService $s */
$s = $sl->get('UnicaenTbl\Service\QueryGenerator');


//$p = $s->updateProcedures();


$s = $sl->get('applicationService');

$r = new \Application\Entity\Service\Recherche();

$r->setIntervenant($sl->get('applicationIntervenant')->get(35424));

$d = $s->getExportPdfData($r);

?>
<table class="table table-bordered table-condensed">
    <thead>
    <tr>
        <th>Intervenant</th>
        <th>Statut intervenant</th>
        <th>Grade</th>
        <th>Structure d'enseignement</th>
        <th>Type de formation</th>
        <th>Formation ou établissement</th>
        <th>Enseignement ou fonction référentielle</th>
        <th>Service statutaire</th>
        <th>Modification de service du</th>
        <th>Total FI</th>
        <th>Total FA</th>
        <th>Total FC</th>
        <th>Référentiel</th>
        <th>Total HETD</th>
        <th>Service (+/-) *</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($d as $di):
        $s = 0;

        $ls = [
            'structure'      => '------------',
            'type-formation' => '------------',
            'formation'      => '------------',
            'enseignement'   => '------------',
        ];
        foreach ($di['services'] as $ds):
            $s++;

            if ($ls['formation'] != $ds['formation']) {
                $ls['enseignement'] = '------------';
            }
            if ($ls['type-formation'] != $ds['type-formation']) {
                $ls['formation']    = '------------';
                $ls['enseignement'] = '------------';
            }
            if ($ls['structure'] != $ds['structure']) {
                $ls['type-formation'] = '------------';
                $ls['formation']      = '------------';
                $ls['enseignement']   = '------------';
            }
            ?>
            <tr>
                <td><?php echo $s == 1 ? $di['nom'] : '' ?></td>
                <td><?php echo $s == 1 ? $di['statut'] : '' ?></td>
                <td><?php echo $s == 1 ? $di['grade'] : '' ?></td>
                <td><?php echo $ls['structure'] != $ds['structure'] ? $ds['structure'] : '' ?></td>
                <td><?php echo $ls['type-formation'] != $ds['type-formation'] ? $ds['type-formation'] : '' ?></td>
                <td><?php echo $ls['formation'] != $ds['formation'] ? $ds['formation'] : '' ?></td>
                <td><?php echo $ls['enseignement'] != $ds['enseignement'] ? $ds['enseignement'] : '' ?></td>

                <td></td>
                <td></td>
                <td><?php echo \UnicaenApp\Util::formattedFloat($ds['fi']) ?></td>
                <td><?php echo \UnicaenApp\Util::formattedFloat($ds['fa']) ?></td>
                <td><?php echo \UnicaenApp\Util::formattedFloat($ds['fc']) ?></td>
                <td><?php echo \UnicaenApp\Util::formattedFloat($ds['referentiel']) ?></td>
                <td><?php echo \UnicaenApp\Util::formattedFloat($ds['total']) ?></td>
                <td></td>
            </tr>
            <?php

            $ls = $ds;

        endforeach; ?>
        <tr class="total">
            <td>Total <?php echo $di['nom'] ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td><?php echo \UnicaenApp\Util::formattedFloat($di['service-du']) ?></td>
            <td><?php echo \UnicaenApp\Util::formattedFloat($di['modif-service-du']) ?></td>
            <td><?php echo \UnicaenApp\Util::formattedFloat($di['fi']) ?></td>
            <td><?php echo \UnicaenApp\Util::formattedFloat($di['fa']) ?></td>
            <td><?php echo \UnicaenApp\Util::formattedFloat($di['fc']) ?></td>
            <td><?php echo \UnicaenApp\Util::formattedFloat($di['referentiel']) ?></td>
            <td><?php echo \UnicaenApp\Util::formattedFloat($di['total']) ?></td>
            <td<?php if ($di['solde'] < 0) echo ' class="solde-negatif"' ?>><?php echo \UnicaenApp\Util::formattedFloat($di['solde']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
