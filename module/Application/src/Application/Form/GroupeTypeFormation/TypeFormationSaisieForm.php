<?php

namespace Application\Form\GroupeTypeFormation;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;

/**
 * Description of TypeFormationSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class TypeFormationSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;



    public function init()
    {
        $hydrator = new TypeFormationHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'    => 'groupe-type-formation',
            'options' => [
                'label' => "Groupe de type de formation",
            ],
            'type'    => 'Hidden',
        ]);

        $this->add([
            'name'    => 'libelle-court',
            'options' => [
                'label' => "Libelle Court",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle-long',
            'options' => [
                'label' => "Libelle Long",
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
            'name'       => 'source',
            'options'    => [
                'label' => 'Source',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);
        $this->get('source')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceSource()->getList()));

        $this->add([
            'name'    => 'statutaire',
            'options' => [
                'label'           => "Service statutaire",
                "checked_value"   => 'true',
                "unchecked_value" => 'false',
            ],
            'type'    => 'Checkbox',
        ]);

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
            'libelle-court' => [
                'required' => true,
            ],

            'libelle-long' => [
                'required' => true,
            ],
            'source'       => [
                'required' => true,
            ],
            'statutaire'   => [
                'required' => true,
            ],

        ];
    }

}





class TypeFormationHydrator implements HydratorInterface
{
    use SourceServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                $data
     * @param  \Application\Entity\Db\TypeFormation $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setGroupe($this->getServiceGroupeTypeFormation()->getById($data['groupe-type-formation']));
        $object->setLibelleCourt($data['libelle-court']);
        $object->setLibelleLong($data['libelle-long']);
        $object->setSourceCode($data['source-code']);
        if (array_key_exists('source', $data)) {
            $object->setSource($this->getServiceSource()->get($data['source']));
        }
        $object->setServiceStatutaire($data['statutaire']=='true');

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\TypeFormation $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                      => $object->getId()
            , 'groupe-type-formation' => $object->getGroupe()->getId()
            , 'libelle-court'         => $object->getLibelleCourt()
            , 'libelle-long'          => $object->getLibelleLong()
            , 'statutaire'    => $object->isServiceStatutaire()?'true':'false'
            , 'source-code'           => $object->getSourceCode()
            , 'source'                => ($s = $object->getSource()) ? $s->getId() : null,

        ];

        return $data;
    }
}
