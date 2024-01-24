<?php

namespace Paiement\Form\DomaineFonctionnel;

use Application\Form\AbstractForm;
use Application\Service\Traits\SourceServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;

/**
 * Description of DomaineFonctionnelSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class DomaineFonctionnelSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;


    public function init()
    {
        $hydrator = new DomaineFonctionnelHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'source-code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name' => 'source',
            'type' => 'Hidden',
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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'libelle' => [
                'required' => true,
            ],

            'source' => [
                'required' => true,
            ],

            'source-code' => [
                'required' => true,
            ],

        ];
    }

}





class DomaineFonctionnelHydrator implements HydratorInterface
{
    use SourceServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                     $data
     * @param \Paiement\Entity\Db\DomaineFonctionnel $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setSourceCode($data['source-code']);
        if (array_key_exists('source', $data)) {
            $object->setSource($this->getServiceSource()->get($data['source']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Paiement\Entity\Db\DomaineFonctionnel $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'            => $object->getId()
            , 'libelle'     => $object->getLibelle()
            , 'source-code' => $object->getSourceCode()
            , 'source'      => ($s = $object->getSource()) ? $s->getId() : null,

        ];

        return $data;
    }
}   
    