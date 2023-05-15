<?php

namespace OffreFormation\Form\EtapeCentreCout;

use Application\Form\AbstractForm;
use Laminas\Form\Element\Select;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Form\EtapeCentreCout\Traits\ElementCentreCoutFieldsetAwareTrait;
use Paiement\Entity\Db\CentreCout;
use Paiement\Service\CentreCoutServiceAwareTrait;
use RuntimeException;


/**
 * Formulaire de saisie, pour chacun des éléments d'une étape, des centres de coûts
 * pour chaque type d'heures éligible.
 *
 */
class EtapeCentreCoutForm extends AbstractForm
{
    use CentreCoutServiceAwareTrait;
    use ElementCentreCoutFieldsetAwareTrait;

    /**
     * Etape
     *
     * @var Etape
     */
    protected $etape;

    /**
     * Types d'heures.
     *
     * @var TypeHeures[]
     */
    protected $typesHeures;

    /**
     * Centres de couts pour chaque (code de) type d'heures.
     *
     * @var CentreCout[][]
     */
    protected $centresCouts = [];



    public function init()
    {
        $this->setName('etape-centre-cout');
        $this->setAttribute('class', 'etape-centre-cout');
        $hydrator = new EtapeCentreCoutFormHydrator;
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
            $this->add($this->createSelectElement($th));
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
        $f = $this->getFieldsetOffreFormationEtapeCentreCoutElementCentreCout();
        /* @var $f ElementCentreCoutFieldset */
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
    private function createSelectElement(TypeHeures $th)
    {
        $element = new Select($th->getCode());
        $element
            ->setLabel($th->getLibelleCourt())
            ->setValueOptions(['' => '(Aucun)'] + $this->getServiceCentreCout()->formatCentresCouts($this->getCentresCouts($th)))
            ->setAttribute('class', 'form-control type-heures header-select selectpicker')
            ->setAttribute('data-live-search', 'true');

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
                /* @var $elFieldset ElementCentreCoutFieldset */

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



    /**
     * Retourne les centres de coûts possibles pour le type d'heure spécifié.
     *
     * @return CentreCout[]
     */
    protected function getCentresCouts(TypeHeures $th)
    {
        if (!array_key_exists($th->getCode(), $this->centresCouts)) {
            $centresCout = [];
            $elements    = $this->getEtape()->getElementPedagogique();
            foreach ($elements as $element) {
                $elFieldset = $this->get('EL' . $element->getId());
                /* @var $elFieldset ElementCentreCoutFieldset */

                $elementCentresCout = $elFieldset->getCentresCouts($th);
                foreach ($elementCentresCout as $centreCout) {
                    if (!isset($centresCout[$centreCout->getId()])) {
                        $centresCout[$centreCout->getId()] = $centreCout;
                    }
                }
            }
            $this->centresCouts[$th->getCode()] = $centresCout;
        }

        return $this->centresCouts[$th->getCode()];
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
}





class EtapeCentreCoutFormHydrator implements HydratorInterface
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