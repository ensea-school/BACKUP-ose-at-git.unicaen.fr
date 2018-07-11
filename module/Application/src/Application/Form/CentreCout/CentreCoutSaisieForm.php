<?php

namespace Application\Form\CentreCout;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
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
                'label' => "Libelle",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'source-code',
            'options' => [
                'label' => "Source Code",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'unite-budgetaire',
            'options' => [
                'label' => "Unite Budgetaire",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'       => 'activite',
            'options'    => [
                'label' => 'Activite',
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
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceCentreCout()->getList()));

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

            'source-code' => [
                'required' => true,
            ],
            'parent' => [
                'required' => false,
            ],
        ];
    }

}





class CentreCoutHydrator implements HydratorInterface
{
    use CcActiviteServiceAwareTrait;
    use TypeRessourceServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use SourceServiceAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                             $data
     * @param  \Application\Entity\Db\CentreCout $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setSourceCode($data['source-code']);
        $object->setUniteBudgetaire($data['unite-budgetaire']);
        if (array_key_exists('activite', $data)) {
            $object->setActivite($this->getServiceccActivite()->get($data['activite']));
        }
        if (array_key_exists('type-ressource', $data)) {
            $object->setTypeRessource($this->getServiceTypeRessource()->get($data['type-ressource']));
        }
        if (array_key_exists('parent', $data)) {
            $object->setParent($this->getServiceCentreCout()->get($data['parent']));
        }
        $object->setSource($this->getServiceSource()->getOse());

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\CentreCout $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                 => $object->getId()
            , 'code'             => $object->getCode()
            , 'libelle'          => $object->getLibelle()
            , 'source-code'      => $object->getSourceCode()
            , 'unite-budgetaire' => $object->getUniteBudgetaire()
            , 'activite'         => ($s = $object->getActivite()) ? $s->getId() : null,
            'type-ressource'     => ($s = $object->getTypeRessource()) ? $s->getId() : null,
            'parent'             => ($s = $object->getParent()) ? $s->getId() : null,

        ];

        return $data;
    }
}   
    