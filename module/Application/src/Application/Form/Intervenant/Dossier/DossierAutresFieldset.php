<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;

/**
 * Description of DossierAutresFieldset
 *
 */
class DossierAutresFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {

        $listChampsAutres = $this->getOption('listChampsAutres');
        foreach ($listChampsAutres as $champ) {
            $this->add([
                'name'    => 'champ-autre-' . $champ->getId(),
                'options' => [
                    'label'         => $champ->getLibelle(),
                    'label_options' => ['disable_html_escape' => true],
                ],
                'type'    => 'Text',//pour le moment on force le champs en type input
            ]);
            $champAutreElement = $this->get('champ-autre-' . $champ->getId());
            if (!empty($champ->getDescription())) {
                $champAutreElement->setAttribute('info_icon', $champ->getDescription());
            }
            if ($champ->isObligatoire()) {
                $champAutreElement->setLabel($champ->getLibelle() . ' <span class="text-danger">*</span>');
            }
        }


        return $this;
    }



    public function getInputFilterSpecification()
    {
        return [];
    }
}