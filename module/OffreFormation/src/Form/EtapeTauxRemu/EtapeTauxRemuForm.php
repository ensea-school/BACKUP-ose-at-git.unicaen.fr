<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\TypeHeures;
use Application\Form\AbstractForm;
use Laminas\Form\Element\Select;
use Laminas\Hydrator\HydratorInterface;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Service\TauxRemuServiceAwareTrait;
use RuntimeException;


/**
 * Formulaire de saisie, pour chacun des éléments d'une étape, des taux de rému
 *
 */
class EtapeTauxRemuForm extends AbstractForm
{
    use TauxRemuServiceAwareTrait;
    use ElementTauxRemuFieldsetAwareTrait;

    /**
     * Etape
     *
     * @var Etape
     */
    protected $etape;

    /**
     *
     * @var TauxRemu[][]
     */
    protected $tauxRemus = [];



    public function init()
    {
        $this->setName('etape-taux-remu');
        $this->setAttribute('class', 'etape-taux-remu');
        $hydrator = new EtapeTauxRemuFormHydrator;
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(Etape::class);
    }



    public function build()
    {
        $elements = $this->getEtape()->getElementPedagogique();
        $this->add($this->createSelectElement());
        foreach ($elements as $element) {
            $this->add($this->createFieldset($element));
        }

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    private function createFieldset(ElementPedagogique $element)
    {
        $f = $this->getFieldsetEtapeTauxRemuElementTauxRemu();
        /* @var $f ElementTauxRemuFieldset */
        $f->setName('EL' . $element->getId());
        $f->setElementPedagogique($element);

        return $f;
    }


    /**
     *
     * @param Etape $object
     *
     * @return self
     */
    public function setObject($object)
    {
        if ($object instanceof Etape) {
            $this->setEtape($object);
            $this->build();
        }

        return parent::setObject($object);
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $elements = $this->getEtape()->getElementPedagogique();
        $filters  = [];
        foreach ($elements as $element) {
            $filters['EL' . $element->getId()] = [
                'required' => false,
            ];
        }

        return $filters;
    }



    /**
     * Retourne les taux de rémunération possible
     *
     * @return TauxRemu[]
     */
    public function getTauxRemus()
    {
            $elements  = $this->getEtape()->getElementPedagogique();
            foreach ($elements as $element) {
                $elFieldset = $this->get('EL' . $element->getId());
                /* @var ElementTauxRemuFieldset $elFieldset */

                $elementTauxRemus = $elFieldset->getTauxRemu();
                foreach ($elementTauxRemus as $elementTauxRemu) {
                    if (!isset($elementTauxRemus[$elementTauxRemu->getId()])) {
                        $elementTauxRemus[$elementTauxRemu->getId()] = $elementTauxRemus;
                    }
                }
            }
            $this->tauxRemus = $elementTauxRemus;

        return $this->tauxRemus;
    }



    /**
     * Retourne l'étape courante (si l'objet a été préalablement associé)
     *
     * @return Etape
     */
    public function getEtape()
    {
        if (!$this->etape) {
            throw new RuntimeException('Aucune étape spécifiée.');
        }

        return $this->etape;
    }



    public function setEtape(Etape $etape)
    {
        $this->etape = $etape;

        return $this;
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
            ->setLabel('tauxRemu')
            ->setValueOptions(['' => '(Aucun)'] + $this->getServiceTauxRemu()->getTauxRemus())
            ->setAttribute('class', 'form-control type-heures header-select selectpicker')
            ->setAttribute('data-live-search', 'true');

        return $element;
    }
}





class EtapeTauxRemuFormHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param Etape $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Etape $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id' => $object->getId(),
        ];

        $elements = $object->getElementPedagogique();
        foreach ($elements as $element) {
            $data['EL' . $element->getId()] = $element;
        }

        return $data;
    }
}