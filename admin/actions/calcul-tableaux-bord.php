<?php

/** @var \Application\Controller\WorkflowController $wf */
$wf = $oa->getController(\Application\Controller\WorkflowController::class);

// les plafonds seront aussi calculés!
$wf->calculTableauxBordAction();