<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\Service;


/**
 * Description of Recherche
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Recherche extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this   ->setAttribute('method', 'get')
                ->setAttribute('class', 'service-recherche')
         ;

        $intervenant = new Select('intervenant');
        $intervenant->setLabel('Intervenant :');
        $this->add($intervenant);

        $element = new Select('element-pedagogique');
        $element->setLabel('Enseignement ou responsabilité :');
        $this->add($element);

        $etape = new Select('etape');
        $etape->setLabel('Formation :');
        $this->add($etape);

        $structureEns = new Select('structure-ens');
        $structureEns->setLabel('Structure d\'enseignement :');
        $this->add($structureEns);

        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Afficher',
                'class' => 'btn btn-primary',
            ),
        ));
    }

    /**
     *
     * @param Service[] $services
     */
    public function populateOptions( $services )
    {
        $intervenant = $this->getServiceLocator()->getServiceLocator()->get('ApplicationIntervenant');

        $intervenants = $intervenant->getList();

        $this->get('intervenant')->setValueOptions( \UnicaenApp\Util::collectionAsOptions($intervenants) );

        //$intervenants   = array();
        $elements       = array();
        $etapes         = array();
        $structuresEns  = array();
        foreach( $services as $service ){
          //  if ($intervenant = $service->getIntervenant()){
          //      $intervenants[$intervenant->getId()] = (string)$intervenant;
          //  }
            if ($structureEns = $service->getStructureEns()){
                $structuresEns[$structureEns->getId()]   = (string)$structureEns;
            }
            if ($element = $service->getelementPedagogique()){
                $elements[$element->getId()]     = (string)$element;
                $etape = $element->getEtape();
                $etapes[$etape->getId()]       = (string)$etape;
            }
        }

        //asort( $intervenants );
        asort( $elements );
        asort( $etapes );
        asort( $structuresEns );

        //$this->get('intervenant')->setValueOptions( array('' => '(Tous)') + $intervenants );
        $this->get('element-pedagogique')->setValueOptions( array('' => '(Tous)') + $elements );
        $etapeSelect = $this->get('etape')->setValueOptions( array('' => '(Toutes)') + $etapes );
        $structureEnsSelect = $this->get('structure-ens')->setValueOptions( array('' => '(Toutes)') + $structuresEns );
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'intervenant' => array(
                'required' => false
            ),
            'etape' => array(
                'required' => false,
            ),
            'structure-ens' => array(
                'required' => false,
            ),
            'element-pedagogique' => array(
                'required' => false,
            ),
        );
    }
}