<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;

/**
 * Description of DossierAutresFieldset
 *
 */
class DossierAutresFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use DossierAutreServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {
        $champsAutres = $this->getServiceDossierAutre()->getList();

        foreach ($champsAutres as $champ) {
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