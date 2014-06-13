<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Select;
use Zend\Form\FormInterface;
use Application\Entity\Db\Etape;

/**
 * Description of ElementModulateursSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtapeModulateursSaisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Etape
     *
     * @var Etape
     */
    protected $etape;


    public function init()
    {
        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('EtapeModulateursFormHydrator');
        $this->setHydrator($hydrator);
     }

    /**
     * Retourne la liste des types de modulateurs
     *
     * @return \Application\Entity\Db\Modulateur[]
     */
    public function getTypesModulateurs()
    {
        $etape = $this->getEtape();
        if (! $etape){
            throw new \Common\Exception\RuntimeException('Etape non spécifiée');
        }
        $serviceTypeModulateur = $this->getServiceLocator()->getServiceLocator()->get('applicationTypeModulateur');
        return $serviceTypeModulateur->getList( $serviceTypeModulateur->finderByStructure($etape->getStructure()) );
    }

    public function getEtape()
    {
        return $this->etape;
    }

    public function setEtape(Etape $etape)
    {
        $this->etape = $etape;
        return $this;
    }

    protected function build()
    {
        $etape = $this->getEtape();
        if (! $etape){
            throw new \Common\Exception\RuntimeException('Etape non spécifiée : construction du formulaire impossible');
        }

        $elements = $etape->getElementPedagogique();
        foreach( $elements as $element ){
            $mf = $this->getServiceLocator()->get('ElementModulateursFieldset');
            $mf->setName('EL'.$element->getId());
            $this->add($mf);
        }
    }

    /**
     *
     * @param Etape $object
     * @return self
     */
    public function setObject($object)
    {
        if ($object instanceof Etape){
            $this->setEtape ($object);
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
        $etape = $this->getEtape();
        if (! $etape){
            throw new \Common\Exception\RuntimeException('Etape non spécifiée : construction des filtres du formulaire impossible');
        }

        $elements = $etape->getElementPedagogique();
        foreach( $elements as $element ){
            $filters['EL'.$element->getId()] = array(
                'required' => false
            );
        }
        return $filters;
    }

}