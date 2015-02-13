<?php

namespace Application\Form\OffreFormation\EtapeCentreCout;

use Zend\Form\Fieldset;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\CentreCout;
use Application\Entity\Db\Structure;
use Common\Exception\RuntimeException;
use UnicaenApp\Util;
use Zend\Form\Element\Select;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Fieldset de saisie d'un centre de coût pour chacun des types d'heures éligibles
 * d'un élément pédagogique.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementCentreCoutSaisieFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * 
     */
    public function init()
    {
        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('ElementCentreCoutFieldsetHydrator');
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
        $element = $this->getElementPedagogique();
        if (!$element) {
            throw new RuntimeException('Elément pédagogique non spécifié');
        }

        return $element->getTypeHeures();
    }

    /**
     * Retourne les centres de coûts possibles pour le type d'heure spécifié.
     *
     * @return CentreCout[]
     */
    public function getCentresCouts(TypeHeures $th)
    {
        $structure = $this->getStructure();
        $f         = function(CentreCout $cc) use ($structure) { return $cc->getStructure() === $structure; };
        
        return $th->getCentreCout()->filter($f);
    }

    /**
     * 
     */
    public function build()
    {
        $typesHeures = $this->getTypesHeures();
        foreach ($typesHeures as $th) {
            $this->add($this->createSelectElement($th));
        }
    }

    /**
     * 
     * @param TypeHeures $th
     * @return Select
     */
    private function createSelectElement(TypeHeures $th)
    {
        $element = new Select($th->getCode());
        $element
                ->setLabel($th->getLibelleCourt())
                ->setValueOptions(['' => '(Aucun)'] + Util::collectionAsOptions($this->getCentresCouts($th)))
                ->setAttribute('class', 'type-heures');
            
        return $element;
    }
    
    /**
     *
     * @param ElementPedagogique $object
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
        $filters     = array();
        foreach ($typesHeures as $th) {
            $filters[$th->getCode()] = array(
                'required' => false
            );
        }
        
        return $filters;
    }
    
    /**
     * @var ElementPedagogique
     */
    protected $elementPedagogique;

    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }

    public function setElementPedagogique(ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique = $elementPedagogique;
        return $this;
    }
    
    /**
     * @var Structure
     */
    protected $structure;

    public function getStructure()
    {
        return $this->structure;
    }

    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;
        return $this;
    }
}