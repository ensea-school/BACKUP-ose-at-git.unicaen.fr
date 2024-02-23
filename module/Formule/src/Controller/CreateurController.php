<?php

namespace Formule\Controller;

use Application\Controller\AbstractController;
use Formule\Service\CalculateurServiceAwareTrait;
use UnicaenVue\View\Model\VueModel;


/**
 * Description of CreateurController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CreateurController extends AbstractController
{
    use CalculateurServiceAwareTrait;

    public function indexAction()
    {
        $variables = [
            'formule' => [
                'code' => 'mon code',
            ],
            'variables' => [
                'Intervenant'                 => $this->getServiceCalculateur()->getIntervenantVariables(),
                'Volume horaire'              => $this->getServiceCalculateur()->getVolumeHoraireVariables(),
            ],
            'resultat' => $this->getServiceCalculateur()->getVolumeHoraireResultats(),
        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('formule/createur/createur');
        $vueModel->setVariables($variables);
        return $vueModel;
    }

}