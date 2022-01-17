<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Entity\Db\StatutIntervenant;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;

/**
 * Description of DossierStatutFieldset
 *
 */
class DossierStatutFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
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

        $statutIntervenant = $this->getOption('statutIntervenant');
        $intervenant       = $this->getOption('intervenant');
        /*On va chercher les statuts que l'intervenant possède
        déjà pour ne pas les afficher dans la liste car il
        ne peut pas avoir deux fois le même statut*/
        $intervernants = $this->getServiceIntervenant()->getIntervenants($intervenant);
        $statuts       = [];
        foreach ($intervernants as $intervenant) {
            if ($intervenant->estNonHistorise() && $intervenant->getStatut()) {
                $statuts[] = $intervenant->getStatut()->getCode();
            }
        }
        $statutSelectable = $this->getServiceStatutIntervenant()->getStatutSelectable($statutIntervenant);
        foreach ($statutSelectable as $k => $statut) {
            if (in_array($statut->getCode(), $statuts) && $statut->getCode() != $statutIntervenant->getCode()) {
                unset($statutSelectable[$k]);
            }
        }
        //Si statut intervenant n'est pas selectionnable dans la liste alors liste en lecture seule
        if ($statutIntervenant->getPeutChoisirDansDossier() || $statutIntervenant->getCode() == 'AUTRES') {
            $this->get('statut')
                ->setValueOptions(['' => '(Sélectionnez un statut)'] + \UnicaenApp\Util::collectionAsOptions($statutSelectable));
        } else {
            $this->get('statut')
                ->setValueOptions(\UnicaenApp\Util::collectionAsOptions([$statutIntervenant]));
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