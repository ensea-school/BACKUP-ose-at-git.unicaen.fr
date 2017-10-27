<?php

namespace Application\Form\OffreFormation\TauxMixite;

use Application\Entity\Db\Traits\ElementPedagogiqueAwareTrait;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractFieldset;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\TypeHeures;
use Zend\Form\Element\Text;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Fieldset de saisie d'un centre de coûts pour chacun des types d'heures éligibles
 * d'un élément pédagogique.
 *
 */
class TauxMixiteFieldset extends AbstractFieldset
{
    use ElementPedagogiqueAwareTrait;
    use \Application\Service\Traits\ElementPedagogiqueAwareTrait;



    public function init()
    {
        $hydrator = new TauxMixiteFieldsetHydrator;
        $hydrator->setServiceElementPedagogique($this->getServiceElementPedagogique());
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(ElementPedagogique::class);
    }



    /**
     * Retourne la liste des types d'heures associés à l'élément pédagogique.
     *
     * @return TypeHeures[]
     */
    public function getTypesHeures()
    {
        return $this->getElementPedagogique()->getTypeHeures();
    }



    private function build()
    {
        $typesHeures = $this->getTypesHeures();
        foreach ($typesHeures as $th) {
            $this->add($this->createElement($th));
        }
    }



    /**
     *
     * @param TypeHeures $th
     *
     * @return Text
     */
    private function createElement(TypeHeures $th)
    {
        $element = new Text($th->getCode());
        $element
            ->setLabel($th->getLibelleCourt())
            ->setAttribute('class', 'type-heures form-control');

        return $element;
    }



    /**
     *
     * @param ElementPedagogique $object
     *
     * @return self
     */
    public function setObject($object)
    {
        if ($object instanceof ElementPedagogique) {
            $this->setElementPedagogique($object);
            $this->build();
        }

        return parent::setObject($object);
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $typesHeures = $this->getTypesHeures();
        $filters     = [];
        foreach ($typesHeures as $th) {
            $filters[$th->getCode()] = [
                'required' => false,
            ];
        }

        return $filters;
    }
}





class TauxMixiteFieldsetHydrator implements HydratorInterface
{
    use \Application\Service\Traits\ElementPedagogiqueAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array              $data
     * @param  ElementPedagogique $element
     *
     * @return object
     */
    public function hydrate(array $data, $element)
    {
        $fi = FloatFromString::run(isset($data[TypeHeures::FI]) ? $data[TypeHeures::FI] : 0 ) / 100;
        $fc = FloatFromString::run(isset($data[TypeHeures::FC]) ? $data[TypeHeures::FC] : 0 ) / 100;
        $fa = FloatFromString::run(isset($data[TypeHeures::FA]) ? $data[TypeHeures::FA] : 0 ) / 100;

        $this->getServiceElementPedagogique()->forcerTauxMixite($element, $fi, $fc, $fa );

        return $element;
    }



    /**
     * Extract values from an object
     *
     * @param  ElementPedagogique $element
     *
     * @return array
     */
    public function extract($element)
    {
        $data = [];

        foreach ($element->getTypeHeures() as $th) {
            $data[$th->getCode()] = StringFromFloat::run($element->getTaux($th) * 100, false);
        }

        return $data;
    }
}