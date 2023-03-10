<?php

namespace Dossier\Form;

use Intervenant\Entity\Db\Statut;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;

/**
 * Description of DossierStatutFieldset
 *
 */
class DossierStatutFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutServiceAwareTrait;
    use IntervenantServiceAwareTrait;

    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {


        /**
         * Statut intervenant
         */
        $this->add([
            'name'       => 'statut',
            'options'    => [
                'label'         => 'Quel est votre statut ? <span class="text-danger">*</span>',
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
            ],
            'type'       => 'Select',
        ]);

        
        /** @var Statut $statut */
        $statut      = $this->getOption('statut');
        $intervenant = $this->getOption('intervenant');
        /*On va chercher les statuts que l'intervenant possède
        déjà pour ne pas les afficher dans la liste, car il
        ne peut pas avoir deux fois le même statut*/
        $intervernants = $this->getServiceIntervenant()->getIntervenants($intervenant);
        $statuts       = [];
        foreach ($intervernants as $intervenant) {
            if ($intervenant->estNonHistorise() && $intervenant->getStatut()) {
                $statuts[] = $intervenant->getStatut()->getCode();
            }
        }
        $statutSelectable = $this->getServiceStatut()->getStatutSelectable($statut);
        $this->get('statut')
            ->setValueOptions(['' => '(Sélectionnez un statut)'] + \UnicaenApp\Util::collectionAsOptions($statutSelectable));


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $spec = [
            'statut' => [
                'required' => false,
            ],
        ];

        return $spec;
    }
}