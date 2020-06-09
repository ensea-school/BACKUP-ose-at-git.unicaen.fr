<?php

namespace Application\Form\CentreCout;

use Application\Form\AbstractForm;
use Application\Hydrator\CentreCoutHydrator;
use Zend\Form\Element\Csrf;
use Application\Service\Traits\CcActiviteServiceAwareTrait;
use Application\Service\Traits\TypeRessourceServiceAwareTrait;
use Application\Service\Traits\CentreCoutServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;

/**
 * Description of CentreCoutSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class CentreCoutSaisieForm extends AbstractForm
{
    use CcActiviteServiceAwareTrait;
    use TypeRessourceServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use SourceServiceAwareTrait;



    public function init()
    {
        $hydrator = new CentreCoutHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'unite-budgetaire',
            'options' => [
                'label' => "Unité Budgétaire",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'       => 'activite',
            'options'    => [
                'label' => 'Activité',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);
        $this->get('activite')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceCcActivite()->getList()));
        $this->add([
            'name'       => 'type-ressource',
            'options'    => [
                'label' => 'Type Ressource',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);
        $this->get('type-ressource')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeRessource()->getList()));
        $this->add([
            'name'       => 'parent',
            'options'    => [
                'label' => 'Parent',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);
        $this->get('parent')
            ->setEmptyOption("(Aucun)")
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceCentreCout()->getListeParent()));

        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'code' => [
                'required' => true,
            ],

            'libelle' => [
                'required' => true,
            ],

            'activite' => [
                'required' => true,
            ],

            'type-ressource' => [
                'required' => true,
            ],

            'parent'      => [
                'required' => false,
            ],
        ];
    }

}