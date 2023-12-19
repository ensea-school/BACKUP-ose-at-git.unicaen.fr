<?php

use Application\Controller\WorkflowController;

/** @var WorkflowController $wf */
$wf = $oa->getController(WorkflowController::class);

// les plafonds seront aussi calculés!
$wf->calculTableauxBordAction();