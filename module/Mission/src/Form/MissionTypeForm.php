<?php

namespace Mission\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\TypeMission;
use Paiement\Service\TauxRemuServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class MissionTypeForm extends AbstractForm
{
    use SchemaServiceAwareTrait;
    use TauxRemuServiceAwareTrait;

    public function init()
    {
        $ignore = [];
        $this->spec(TypeMission::class, $ignore);
        $this->spec([
            'tauxRemu' => [
                'input' => [
                    'required' => false,
                ],
            ],
        ]);
        $this->spec([
            'tauxRemuMajore' => [
                'input' => [
                    'required' => false,
                ],
            ],
        ]);
        $this->build();
        $this->setValueOptions('tauxRemu', $this->getServiceTauxRemu()->getTauxRemusAnneeWithValeur());
        $this->setValueOptions('tauxRemuMajore', $this->getServiceTauxRemu()->getTauxRemusAnneeWithValeur());
        $this->get('tauxRemu')->setEmptyOption("- Aucun taux par défaut -");
        $this->get('tauxRemuMajore')->setEmptyOption("- Utilisation du taux normal -");

        $this->addSecurity();
        $this->addSubmit();

        $this->setLabels([
            'tauxRemu'                => 'Taux par défaut',
            'tauxRemuMajore'          => 'Taux majoré',
            'accompagnementEtudiants' => 'Accompagnement d\'étudiants',
            'besoinFormation'         => 'Heures de formation disponibles',
        ]);

        return $this;
    }

}
