<?php

namespace Application\Form\OffreFormation\EtapeCentreCout;

use Application\Entity\Db\CentreCout;
use Application\Service\Traits\CentreCoutAwareTrait;
use Application\Service\Traits\CentreCoutEpAwareTrait;
use Zend\Form\Fieldset;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\TypeHeures;
use Common\Exception\RuntimeException;
use Zend\Form\Element\Select;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\CentreCoutEp;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Fieldset de saisie d'un centre de coûts pour chacun des types d'heures éligibles
 * d'un élément pédagogique.
 *
 */
class ElementCentreCoutFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use CentreCoutAwareTrait;
    use CentreCoutEpAwareTrait;

    /**
     * element pédagogique associé
     *
     * @var ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var array
     */
    private $centresCouts = [];



    /**
     *
     */
    public function init()
    {
        $hydrator = new ElementCentreCoutFieldsetHydrator;
        $hydrator->setServiceCentreCout($this->getServiceCentreCout());
        $hydrator->setServiceCentreCoutEp($this->getServiceCentreCoutEp());
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass('Application\Entity\Db\ElementPedagogique');
    }



    /**
     * Retourne la liste des types d'heures associés à l'élément pédagogique.
     *
     * @return TypeHeures[]
     */
    public function getTypesHeures()
    {
        $filter = function (TypeHeures $typeHeures) {
            return $typeHeures->getEligibleCentreCoutEp();
        };

        return $this->getElementPedagogique()->getTypeHeures()->filter($filter);
    }



    /**
     * Retourne la liste des centres de coût associés à un type d'heures en particulier
     *
     * @param TypeHeures $th
     *
     * @return CentreCout[]
     */
    public function getCentresCouts(TypeHeures $th) // à revoir ! ! !
    {
        if (!isset($this->centresCouts[$th->getCode()])) {
            $filter = function (CentreCout $centreCout) use ($th) {
                return $centreCout->getTypeHeures()->contains($th);
            };
            $this->centresCouts[$th->getCode()]
                = $this->getElementPedagogique()->getStructure()->getCentreCout()->filter($filter);
        }

        return $this->centresCouts[$th->getCode()];
    }



    /**
     *
     */
    private function build()
    {
        $typesHeures = $this->getTypesHeures();
        foreach ($typesHeures as $th) {
            $this->add($this->createSelectElement($th));
        }
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
            ->setAttribute('class', 'type-heures selectpicker');

        return $element;
    }



    /**
     *
     * @param ElementPedagogique $object
     * @return self
     */
    public function setObject($object)
    {
        if ($object instanceof ElementPedagogique){
            $this->setElementPedagogique ($object);
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
        $filters = [];
        foreach ($typesHeures as $th) {
            $filters[$th->getCode()] = [
                'required' => false
            ];
        }

        return $filters;
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





class ElementCentreCoutFieldsetHydrator implements HydratorInterface
{
    use CentreCoutAwareTrait;
    use CentreCoutEpAwareTrait;



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
        $newData = array_filter($data);
        $oldData = array_filter($this->extract($element));

        foreach ($element->getTypeHeures() as $th) {
            $code = $th->getCode();

            $newCcId = isset($newData[$code]) ? (int)$newData[$code] : null;
            $oldCcId = isset($oldData[$code]) ? (int)$oldData[$code] : null;

            $creating = !$oldCcId && $newCcId;
            $deleting = $oldCcId && !$newCcId;

            if ($deleting) {
                $ccEp = $element->getCentreCoutEp($th)->first();
                /* @var $ccEp CentreCoutEp */
                $element->removeCentreCoutEp($ccEp);
                $this->getServiceCentreCoutEp()->delete($ccEp);
            }
            if ($creating) {
                $ccEp = $this->getServiceCentreCoutEp()->newEntity();
                $cc = $this->getServiceCentreCout()->get($newCcId);
                $ccEp
                    ->setCentreCout($cc)
                    ->setTypeHeures($th)
                    ->setElementPedagogique($element);
                $this->getServiceCentreCoutEp()->save($ccEp);
            }
        }

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
            if (($ccEp = $element->getCentreCoutEp($th)->first())) {
                $cc = $ccEp->getCentreCout();
                $ccId = $cc->getId();
                $data[$th->getCode()] = $ccId;
            }
        }

        return $data;
    }
}