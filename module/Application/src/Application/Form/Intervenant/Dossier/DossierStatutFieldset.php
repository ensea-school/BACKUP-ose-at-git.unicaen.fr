<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Entity\Db\StatutIntervenant;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;

/**
 * Description of DossierStatutFieldset
 *
 */
class DossierStatutFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;

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

        //Si statut intervenant n'est pas selectionnable dans la liste alors liste en lecture seule
        /**
         * @var StatutIntervenant $statutIntervenant
         */
        if ($statutIntervenant->getPeutChoisirDansDossier() || $statutIntervenant->getCode() == 'AUTRES') {
            $this->get('statut')
                ->setValueOptions(['' => '(Sélectionnez un statut)'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceStatutIntervenant()->getStatutSelectable($statutIntervenant)));
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