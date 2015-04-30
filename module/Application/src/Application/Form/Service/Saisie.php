<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use Application\Entity\Db\Etablissement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;


/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends Form implements \Zend\InputFilter\InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;


    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return \Application\Entity\Db\Periode[]
     */
    public function getPeriodes()
    {
        $servicePeriode = $this->getServiceLocator()->getServiceLocator()->get('applicationPeriode');
        /* @var $servicePeriode \Application\Service\Periode */
        $periodes = $servicePeriode->getEnseignement();
        return $periodes;
    }

    public function __construct($name = null, $options = [])
    {
        parent::__construct('service', $options);
    }

    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param  object $object
     * @param  int $flags
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        if ($object instanceof \Application\Entity\Db\Service && $object->getTypeVolumeHoraire() ){
            $this->get('type-volume-horaire')->setValue( $object->getTypeVolumeHoraire()->getId() );
        }
        return parent::bind($object, $flags);
    }


    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceSaisieHydrator'));

        // Product Fieldset
        // Here, we define Product fieldset as base fieldset
        $saisie = $this->getServiceLocator()->get('ServiceSaisieFieldset');
//                new SaisieFieldset('saisie');
        //$saisie->setUseAsBaseFieldset(true);
        $this->add($saisie);

        foreach( $this->getPeriodes() as $periode ){
            $pf = $this->getServiceLocator()->get('VolumeHoraireSaisieMultipleFieldset');
            $pf->setName($periode->getCode());
            $this->add($pf);
        }

        $this->add( new Hidden('type-volume-horaire') );

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setAttribute('action', $url(null, [], [], true));
    }

    public function initFromContext()
    {
        $this->get('service')->initFromContext();
    }

    public function saveToContext()
    {
        $this->get('service')->saveToContext();
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification(){
        return [];
    }
}