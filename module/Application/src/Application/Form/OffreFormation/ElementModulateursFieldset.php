<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Select;
use Application\Entity\Db\ElementPedagogique;

/**
 * Description of ElementModulateursFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * element pédagogique associé
     *
     * @var ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * nombre de modulateurs total
     *
     * @var integer
     */
    protected $countModulateurs = 0;

    /**
     * nombre de modulateurs par code de type
     *
     * @var integer[]
     */
    protected $countModulateursByType = [];



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
     * Retourne le nombre total de modulateurs que l'on peut renseigner
     *
     * @param string|null $typeCode
     * @return integer
     */
    public function countModulateurs( $typeCode=null )
    {
        if (empty($typeCode)){
            return $this->countModulateurs;
        }elseif(isset($this->countModulateursByType[$typeCode])){
            return $this->countModulateursByType[$typeCode];
        }else{
            return 0;
        }
    }

    /**
     * Retourne la liste des types de modulateurs
     *
     * @return \Application\Entity\Db\Modulateur[]
     */
    public function getTypesModulateurs()
    {
        $element = $this->getElementPedagogique();
        if (! $element){
            throw new \Common\Exception\RuntimeException('Elément pédagogique non spécifié');
        }
        $serviceTypeModulateur = $this->getServiceLocator()->getServiceLocator()->get('applicationTypeModulateur');
        return $serviceTypeModulateur->getList( $serviceTypeModulateur->finderByElementPedagogique($element) );
    }

    public function init()
    {
        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('ElementModulateursFormHydrator');
        $this->setHydrator($hydrator);
        $this->setAllowedObjectBindingClass('Application\Entity\Db\ElementPedagogique');
    }

    public function build()
    {
        $typesModulateurs = $this->getTypesModulateurs();
        foreach( $typesModulateurs as $typeModulateur ){
            $element = new Select($typeModulateur->getCode());
            $element->setLabel($typeModulateur->getLibelle());
            $values = ['' => ''];
            foreach( $typeModulateur->getModulateur() as $modulateur ){
                $values[$modulateur->getId()] = (string)$modulateur;
            }

            $element->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $values ) );
            $this->add($element);
            $this->countModulateurs++;
            if (! isset($this->countModulateursByType[$typeModulateur->getCode()])){
                $this->countModulateursByType[$typeModulateur->getCode()] = 0;
            }
            $this->countModulateursByType[$typeModulateur->getCode()] ++;
        }
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
        $typesModulateurs = $this->getTypesModulateurs();
        $filters = [];
        foreach( $typesModulateurs as $typeModulateur ){
            $filters[$typeModulateur->getCode()] = [
                'required' => false
            ];
        }
        return $filters;
    }

}