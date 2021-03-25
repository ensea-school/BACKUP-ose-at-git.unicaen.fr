<?php

namespace ExportRh\Controller;

use Application\Controller\AbstractController;
use ExportRh\Service\ExportRhServiceAwareTrait;

class AdministrationController extends AbstractController
{
    use ExportRhServiceAwareTrait;

    public function indexAction()
    {
        $erhs = $this->getExportRhService();

        $intervenantParams = $erhs->getIntervenantExportParams();
        $champs            = $erhs->getIntervenantParamsDescription();

        return compact('intervenantParams', 'champs');
    }

}
