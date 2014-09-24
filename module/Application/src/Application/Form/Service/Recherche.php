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

    /**
     * @var \Zend\Mvc\Controller\Plugin\Url
     */
    protected $urlPlugin;

    /**
     * 
     */
    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $resumeUrl = $url('service/resume');
        $resumeDetailsUrl = $url('service/default', ['action' => 'index']);

        $formId = uniqid();

        $this   ->setAttribute('method', 'get')
                ->setAttribute('class', 'service-recherche')
                ->setAttribute('id', $formId);

        $intervenantUrl = $this->getUrlPlugin()->fromRoute(
                'recherche', 
                array('action' => 'intervenantFind'),
                array('query' => array('having-services' => 1)));

        $intervenant = new \UnicaenApp\Form\Element\SearchAndSelect('intervenant');
        $intervenant
                ->setAutocompleteSource($intervenantUrl)
                ->setLabel('Intervenant :');
        $this->add($intervenant);

        $element = new Select('element-pedagogique');
        $element->setLabel('Enseignement :');
        $this->add($element);

        $etape = new Select('etape');
        $etape->setLabel('Formation :');
        $this->add($etape);

        $structureEns = new Select('structure-ens');
        $structureEns->setLabel('Structure d\'enseignement :');
        $this->add($structureEns);

        $statutInterv = new \Zend\Form\Element\Radio('statut-interv');
        $statutInterv
                ->setValueOptions(array(
                    '' => "Peu importe",
                    'Application\Entity\Db\IntervenantPermanent' => "Permanent",
                    'Application\Entity\Db\IntervenantExterieur' => "Vacataire"))
                ->setValue('')
                ->setLabel("Statut de l'intervenant :");
        $this->add($statutInterv);

        $action = new Hidden('action');
        $action->setValue('afficher');
        $this->add($action);

        /**
         * Submit
         */
        $this->add([
            'name' => 'submit-resume',
            'type'  => 'Button',
            'options' => ['label' => 'Afficher (résumé)'],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'onclick' => '$("#'.$formId.'").attr("action", "'.$resumeUrl.'");',
            ],
        ]);

        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit-details',
            'type'  => 'Button',
            'options' => ['label' => 'Afficher (détails)'],
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-default',
                'onclick' => '$("#'.$formId.'").attr("action", "'.$resumeDetailsUrl.'");',
            ),
        ));
    }

    /**
     * 
     * @return \Zend\Mvc\Controller\Plugin\Url
     */
    protected function getUrlPlugin()
    {
        if (null === $this->urlPlugin) {
            $this->urlPlugin = $this->getServiceLocator()->getServiceLocator()->get('ControllerPluginManager')->get('url');
        }
        return $this->urlPlugin;
    }
    
    /**
     *
     * @param Service[] $services
     */
    public function populateOptions()
    {
        $sl = $this->getServiceLocator()->getServiceLocator();

        $elementPedagogique = $sl->get('ApplicationElementPedagogique'); /* @var $elementPedagogique \Application\Service\ElementPedagogique */
        $etape              = $sl->get('ApplicationEtape');              /* @var $etape \Application\Service\Etape */
        $structure          = $sl->get('ApplicationStructure');          /* @var $structure \Application\Service\Structure */
        $service            = $sl->get('ApplicationService');            /* @var $service \Application\Service\Service */

        $qb = $elementPedagogique->initQuery()[0];
        $elementPedagogique->join( $service, $qb, 'service' );
        $service->finderByContext( $qb );
        $this->get('element-pedagogique')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Tous)') + $elementPedagogique->getList($qb))
                                                  );

        $qb = $structure->initQuery()[0];
        $structure->join( $service, $qb, 'service' );
        $service->finderByContext( $qb );
        $this->get('structure-ens')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Toutes)') + $structure->getList($qb))
                                                    );

        $qb = $etape->initQuery()[0];
        $etape->join( $elementPedagogique, $qb, 'elementPedagogique' );
        $elementPedagogique->join( $service, $qb, 'service' );
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
            'statut-interv' => array(
                'required' => false
            ),
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
        $session = $this->getSessionContainer();
        $session->data = $data;        
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
        if (! $object) {
            $object = new \stdClass;
        }
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