<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Application\Form\AbstractFieldset;
use Laminas\Form\Element\Select;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Entity\Db\ElementPedagogique;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Service\TauxRemuServiceAwareTrait;
use RuntimeException;

/**
 * Fieldset de saisie d'un taux de rémunération pour un élément pédagogique.
 *
 */
class ElementTauxRemuFieldset extends AbstractFieldset
{
    use TauxRemuServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;

    /**
     * element pédagogique associé
     *
     * @var ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var array
     */
    private $tauxRemus = [];



    /**
     *
     */
    public function init()
    {
        $hydrator = new ElementTauxRemusFieldsetHydrator();
        $hydrator->setServiceTauxRemu($this->getServiceTauxRemu());
        $hydrator->setServiceElementPedagogique($this->getServiceElementPedagogique());
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(ElementPedagogique::class);
    }



    /**
     * Retourne la liste des taux de rémunération associés
     *
     *
     * @return TauxRemu|null
     */
    public function getTauxRemus()
    {
        if (!isset($this->tauxRemus['tauxRemu'])) {

            $this->tauxRemus['tauxRemu']
                = $this->getServiceTauxRemu()->getTauxRemus();
        }

        return $this->tauxRemus['tauxRemu'];
    }



    /**
     *
     */
    public function build()
    {
        $this->add($this->createSelectElement());
    }



    /**
     *
     *
     * @return Select
     */
    private function createSelectElement()
    {
        $element = new Select('tauxRemu');
        $element
            ->setLabel('taux')
            ->setValueOptions(['' => '(Aucun)'] + $this->getServiceTauxRemu()->formatTauxRemus($this->getTauxRemus()))
            ->setAttribute('class', 'taux-remus selectpicker')
            ->setAttribute('data-live-search', 'true');

        return $element;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters             = [];
        $filters['tauxRemu'] = [
            'required' => false,
        ];

        return $filters;
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
     * Retourne l'élément pédagogique courant (si l'objet a été préalablement associé)
     *
     * @return ElementPedagogique
     */
    public function getElementPedagogique()
    {
        if (!$this->elementPedagogique) {
            throw new RuntimeException('Elément pédagogique non spécifié.');
        }

        return $this->elementPedagogique;
    }



    /**
     * @param ElementPedagogique $elementPedagogique
     *
     * @return $this
     */
    public function setElementPedagogique(ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }

}





class ElementTauxRemusFieldsetHydrator implements HydratorInterface
{
    use TauxRemuServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array              $data
     * @param ElementPedagogique $element
     *
     * @return object
     */
    public function hydrate(array $data, $element)
    {
        $tauxRemu = null;
        if ($data['tauxRemu']) {
            $tauxRemu = $this->getServiceTauxRemu()->get((int)$data['tauxRemu']);
        }
        $this->getServiceElementPedagogique()->updateTauxRemu($element, $tauxRemu);

        return $element;
    }



    /**
     * Extract values from an object
     *
     * @param ElementPedagogique $element
     *
     * @return array
     */
    public function extract($element): array
    {
        $data = [];
        $trEp = $element->getTauxRemuEp();
        if ($trEp) {
            $data['tauxRemu'] = $trEp->getId();
        }

        return $data;
    }
}