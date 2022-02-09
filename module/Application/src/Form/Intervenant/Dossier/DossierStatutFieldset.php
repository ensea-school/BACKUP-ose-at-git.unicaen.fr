<?php

namespace Application\Form\Intervenant\Dossier;

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
        foreach ($statutSelectable as $k => $statut) {
            if (in_array($statut->getCode(), $statuts) && $statut->getCode() != $statut->getCode()) {
                unset($statutSelectable[$k]);
            }
        }
        //Si statut intervenant n'est pas selectionnable dans la liste alors liste en lecture seule
        if ($statut->getDossierSelectionnable() || $statut->isAutres()) {
            $this->get('statut')
                ->setValueOptions(['' => '(Sélectionnez un statut)'] + \UnicaenApp\Util::collectionAsOptions($statutSelectable));
        } else {
            $this->get('statut')
                ->setValueOptions(\UnicaenApp\Util::collectionAsOptions([$statut]));
        }


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $spec = [
            'statut' => [
                'required' => true,
            ],
        ];

        return $spec;
    }
}