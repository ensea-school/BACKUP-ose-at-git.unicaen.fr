<?php

namespace OffreFormation\Form\TauxMixite;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Traits\EtapeAwareTrait;
use Application\Entity\Db\TypeHeures;
use Application\Form\AbstractForm;
use OffreFormation\Form\TauxMixite\Traits\TauxMixiteFieldsetAwareTrait;
use Laminas\Form\Element\Text;
use Laminas\Hydrator\HydratorInterface;


/**
 * Formulaire de saisie, pour chacun des éléments d'une étape, des centres de coûts
 * pour chaque type d'heures éligible.
 *
 */
class TauxMixiteForm extends AbstractForm
{
    use TauxMixiteFieldsetAwareTrait;
    use EtapeAwareTrait;


    /**
     * Types d'heures.
     *
     * @var TypeHeures[]
     */
    protected $typesHeures;



    public function init()
    {
        $this->setName('etape-taux-mixite');
        $this->setAttribute('class', 'etape-taux-mixite');
        $hydrator = new TauxMixiteFormHydrator;
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass(Etape::class);
    }



    public function build()
    {
        $elements = $this->getEtape()->getElementPedagogique();
        foreach ($elements as $element) {
            $this->add($this->createFieldset($element));
        }

        foreach ($this->getTypesHeures() as $th) {
            $this->add($this->createElement($th));
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
        $f = $this->getFieldsetOffreFormationTauxMixiteTauxMixite();

        $f->setName('EL' . $element->getId());
        $f->setElementPedagogique($element);

        return $f;
    }



    /**
     *
     * @param TypeHeures $th
     *
     * @return Select
     */
    private function createElement(TypeHeures $th)
    {
        $element = new Text($th->getCode());
        $element
            ->setLabel($th->getLibelleCourt())
            ->setAttribute('class', 'form-control type-heures');

        return $element;
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
        foreach ($this->getTypesHeures() as $th) {
            $filters[$th->getCode()] = [
                'required' => false,
            ];
        }
        foreach ($elements as $element) {
            $filters['EL' . $element->getId()] = [
                'required' => false,
            ];
        }

        return $filters;
    }



    /**
     * Recherche, parmi les éléments de l'étape, des types d'heures distincts éligibles
     *
     * @return TypeHeures[]
     */
    public function getTypesHeures()
    {
        if (empty($this->typesHeures)) {
            $this->typesHeures = [];

            $elements = $this->getEtape()->getElementPedagogique();
            foreach ($elements as $element) {
                $elFieldset = $this->get('EL' . $element->getId());
                /* @var $elFieldset TauxMixiteFieldset */

                foreach ($elFieldset->getTypesHeures() as $typeHeures) {
                    if (!isset($this->typesHeures[$typeHeures->getCode()])) {
                        $this->typesHeures[$typeHeures->getCode()] = $typeHeures;
                    }
                }
            }

            uasort($this->typesHeures, function ($a, $b) {
                /* @var $a TypeHeures */
                /* @var $b TypeHeures */
                return $a->getOrdre() - $b->getOrdre();
            });
        }

        return $this->typesHeures;
    }

}





class TauxMixiteFormHydrator implements HydratorInterface
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