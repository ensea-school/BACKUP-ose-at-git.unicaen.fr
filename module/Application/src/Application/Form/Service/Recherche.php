<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Hidden;
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

    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;

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

        $action = new Hidden('action');
        $action->setValue('afficher');
        $this->add($action);

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
    public function populateOptions()
    {
        $sl = $this->getServiceLocator()->getServiceLocator();

        $intervenant        = $sl->get('ApplicationIntervenant');
        $elementPedagogique = $sl->get('ApplicationElementPedagogique');
        $etape              = $sl->get('ApplicationEtape');
        $structure          = $sl->get('ApplicationStructure');
        $service            = $sl->get('ApplicationService');

        $qb = $intervenant->initQuery()[0];
        $intervenant->join( $service, $qb, 'id', 'intervenant' );
        $service->finderByContext( $qb );
        $this->get('intervenant')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Tous)') + $intervenant->getList($qb))
                                                  );

        $qb = $elementPedagogique->initQuery()[0];
        $elementPedagogique->join( $service, $qb, 'id', 'elementPedagogique' );
        $service->finderByContext( $qb );
        $this->get('element-pedagogique')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Tous)') + $elementPedagogique->getList($qb))
                                                  );

        $qb = $structure->initQuery()[0];
        $structure->join( $service, $qb, 'id', 'structureEns' );
        $service->finderByContext( $qb );
        $this->get('structure-ens')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Toutes)') + $structure->getList($qb))
                                                    );

        $qb = $etape->initQuery()[0];
        $etape->join( $elementPedagogique, $qb, 'id', 'etape' );
        $elementPedagogique->join( $service, $qb, 'id', 'elementPedagogique' );
        $service->finderByContext( $qb );
        $this->get('etape')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Tous)') + $etape->getList($qb))
                                            );
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

    /**
     * Encapsule dans une session les données du formulaire
     *
     * @return self
     */
    public function sessionUpdate()
    {
        $data = $this->getData();
        if (is_object($data)){ // Si l'objet est bindé, alors il faut l'extraire avec l'hydrateur
            $data = $this->getHydrator()->extract($data);
        }
        if ($data['submit']){
            $session = $this->getSessionContainer();
            $session->data = $data;
        }
        return $this;
    }

    /**
     * Hydrate un objet depuis la session
     *
     * Si aucun objet n'est précisé, alors renvoie un objet de type StdClass
     *
     * @param StdClass|null $object
     * @return mixed
     */
    public function hydrateFromSession($object=null)
    {
        if (! $object) $object = new \stdClass;
        $session = $this->getSessionContainer();
        if ($session->offsetExists('data')){
            $data = $session->data;
            $this->getHydrator()->hydrate($data, $object);
        }
        return $object;
    }

    /**
     * Applique au formulaire les données de session
     * 
     * @return self
     */
    public function setDataFromSession()
    {
        $session = $this->getSessionContainer();
        if ($session->offsetExists('data')){
            $this->setData( $session->data );
        }
        return $this;
    }

    /**
     * @return \Zend\Session\Container
     */
    protected function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new \Zend\Session\Container(get_class($this));
        }
        return $this->sessionContainer;
    }
}