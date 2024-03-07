<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Formule\Entity\Db\Formule;
use Formule\Service\CalculateurServiceAwareTrait;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of AdministrationController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{

    public function indexAction()
    {
        $dql = "
        SELECT
          f
        FROM
          ".Formule::class." f
        ORDER BY
            f.libelle
        ";

        $formules = $this->em()->createQuery($dql)->getArrayResult();



        $variables = [
            'canEdit' => $this->isAllowed(Privileges::getResourceId(Privileges::FORMULE_ADMINISTRATION_EDITION)),
            'formules' => $formules,
        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('formule/administration/index');
        $vueModel->setVariables($variables);
        return $vueModel;
    }

}