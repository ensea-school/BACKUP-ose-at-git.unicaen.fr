<?php

/** @var \Application\Controller\WorkflowController $wf */
$wf = $oa->getController(\Application\Controller\WorkflowController::class);

$wf->calculTableauxBordAction();
// les plafonds seront aussi calculés!
//$oa->exec('calcul-tableaux-bord');