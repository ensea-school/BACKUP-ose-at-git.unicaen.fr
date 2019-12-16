<?php

namespace Application\Form\Structure;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;

/**
 * Description of StructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class StructureSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;



    public function init()
    {
        $hydrator = new StructureHydrator();
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
            'name'    => 'enseignement',
            'options' => [
                'label' => "Enseignement",
            ],
            'type'    => 'Checkbox',
        ]);
        $this->add([
            'name'    => 'source-code',
            'options' => [
                'label' => "Source Code",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'plafond-referentiel',
            'options' => [
                'label' => "Plafond Referentiel",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'aff-adresse-contrat',
            'options' => [
                'label' => "Aff Adresse Contrat",
            ],
            'type'    => 'Checkbox',
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

            'libelle-court' => [
                'required' => true,
            ],

            'libelle-long' => [
                'required' => true,
            ],

            'enseignement' => [
                'required' => true,
            ],

            'source' => [
                'required' => true,
            ],

            'aff-adresse-contrat' => [
                'required' => true,
            ],
            'plafond-referentiel' => [
                'required'   => true,
                'validators' => [
                    new \Zend\Validator\Callback([
                        'messages' => [\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            if ($value == null) return true;

                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],

        ];
    }

}





class StructureHydrator implements HydratorInterface
{
    use SourceServiceAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                            $data
     * @param  \Application\Entity\Db\Structure $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelleCourt($data['libelle-court']);
        $object->setLibelleLong($data['libelle-long']);
        $object->setEnseignement($data['enseignement']);
        $object->setSourceCode($data['source-code']);
        $object->setPlafondReferentiel(FloatFromString::run($data['plafond-referentiel']));
        $object->setAffAdresseContrat($data['aff-adresse-contrat']);
        if (array_key_exists('source', $data)) {
            $object->setSource($this->getServiceSource()->get($data['source']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\Structure $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                    => $object->getId()
            , 'code'                => $object->getCode()
            , 'libelle-court'       => $object->getLibelleCourt()
            , 'libelle-long'        => $object->getLibelleLong()
            , 'enseignement'        => $object->isEnseignement()
            , 'source-code'         => $object->getSourceCode()
            , 'plafond-referentiel' => StringFromFloat::run($object->getPlafondReferentiel())
            , 'aff-adresse-contrat' => $object->isAffAdresseContrat()
            , 'source'              => ($s = $object->getSource()) ? $s->getId() : null,

        ];

        return $data;
    }
}   
    
