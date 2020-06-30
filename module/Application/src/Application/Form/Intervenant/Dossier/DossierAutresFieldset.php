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
                    'label' => $champ->getLibelle(),
                ],
                'type'    => 'Text',//pour le moment on force le champs en type input
            ]);
        }


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $spec = [
            'autre1' => [
                'required' => false,
            ],
            'autre2' => [
                'required' => false,
            ],
            'autre3' => [
                'required' => false,
            ],
            'autre4' => [
                'required' => false,
            ],
            'autre5' => [
                'required' => false,
            ],
        ];

        return $spec;
    }
}