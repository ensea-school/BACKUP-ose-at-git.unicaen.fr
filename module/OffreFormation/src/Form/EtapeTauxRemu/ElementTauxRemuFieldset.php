<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Application\Form\AbstractFieldset;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Application\Entity\Db\ElementPedagogique;
use Paiement\Entity\Db\TauxRemu;
use RuntimeException;
use Laminas\Form\Element\Select;
use Laminas\Hydrator\HydratorInterface;

/**
 * Fieldset de saisie d'un taux de rémunération pour un élément pédagogique.
 *
 */
class ElementTauxRemuFieldset extends AbstractFieldset
{
    use TauxRemuServiceAwareTrait;

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
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(ElementPedagogique::class);
    }


    /**
     * Retourne la liste des taux de rémunération associés
     *
     *
     * @return TauxRemu|null
     */
    public function getTauxRemu()
    {
        if (!isset($this->tauxRemus['tauxRemu'])) {

            $this->tauxRemus['tauxRemu']
                = $this->getElementPedagogique()->getStructure()->getTauxRemu();
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
            ->setValueOptions(['' => '(Aucun)'] + $this->getServiceTauxRemu()->getTauxRemus())
            ->setAttribute('class', 'taux-remus selectpicker')
            ->setAttribute('data-live-search', 'true');

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

        return $element;
    }



    /**
     * Extract values from an object
     *
     * @param ElementPedagogique $element
     *
     * @return int
     */
    public function extract($object): array
    {

        $data = [
            'id'       => $object->getId(),
            'libelle'  => $object->getLibelle(),
            'tauxRemu' => $object->getTauxRemu()?->getId(),
        ];


        return $data;
    }
}