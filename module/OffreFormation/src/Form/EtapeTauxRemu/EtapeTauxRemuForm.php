<?php

namespace OffreFormation\Form\EtapeTauxRemu;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
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
     * Centres de couts pour chaque (code de) type d'heures.
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
        foreach ($elements as $element) {
            $this->add($this->createFieldset($element));
        }
        $this->add($this->createSelectElement());


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
        $filters['tauxRemu'] = [
            'required' => false,
        ];
        foreach ($elements as $element) {
            $filters['EL' . $element->getId()] = [
                'required' => false,
            ];
        }

        return $filters;
    }



    /**
     * Retourne les taux de rémunération possibles
     *
     * @return TauxRemu[]
     */
    protected function getTauxRemus()
    {
        if (!array_key_exists('tauxRemu', $this->tauxRemus)) {
            $tauxRems = [];
            $elements = $this->getEtape()->getElementPedagogique();
            foreach ($elements as $element) {
                $elFieldset = $this->get('EL' . $element->getId());
                /* @var $elFieldset ElementTauxRemuFieldset */

                $elementTauxRemu = $elFieldset->getTauxRemus();
                foreach ($elementTauxRemu as $tauxRemu) {
                    if (!isset($tauxRems[$tauxRemu->getId()])) {
                        $tauxRems[$tauxRemu->getId()] = $tauxRemu;
                    }
                }
            }
            $this->tauxRemus['tauxRemu'] = $tauxRems;
        }

        return $this->tauxRemus['tauxRemu'];
    }



    /**
     * Retourne l'étape courante (si l'objet a été préalablement associé)
     *
     * @return Etape
     */
    public
    function getEtape()
    {
        if (!$this->etape) {
            throw new RuntimeException('Aucune étape spécifiée.');
        }

        return $this->etape;
    }



    public
    function setEtape(Etape $etape)
    {
        $this->etape = $etape;

        return $this;
    }



    /**
     *
     *
     * @return Select
     */
    private
    function createSelectElement()
    {
        $element = new Select('tauxRemu');
        $element
            ->setLabel('tauxRemu')
            ->setValueOptions(['' => '(Aucun)'] + $this->getServiceTauxRemu()->formatTauxRemus($this->getTauxRemus()))
            ->setAttribute('class', 'form-control taux-remus header-select selectpicker')
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