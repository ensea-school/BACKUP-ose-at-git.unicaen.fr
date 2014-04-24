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
    public function populateOptions( array $serviceContext )
    {
        $sl = $this->getServiceLocator()->getServiceLocator();

        $intervenant        = $sl->get('ApplicationIntervenant');
        $elementPedagogique = $sl->get('ApplicationElementPedagogique');
        $etape              = $sl->get('ApplicationEtape');
        $structure          = $sl->get('ApplicationStructure');
        $service            = $sl->get('ApplicationService');

        $serviceContext['structureEns'] = $structure->getRepo()->find(8469);

        $qb = $intervenant->initQuery()[0];
        $intervenant->join( $service, $qb, 'id', 'intervenant' );
        $service->finderByFilterArray( $serviceContext, $qb );
        $this->get('intervenant')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Tous)') + $intervenant->getList($qb))
                                                  );

        $qb = $elementPedagogique->initQuery()[0];
        $elementPedagogique->join( $service, $qb, 'id', 'elementPedagogique' );
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        //$qb->join($service->getAlias().".elementPedagogique");
        $service->finderByFilterArray( $serviceContext, $qb );
        $this->get('element-pedagogique')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Tous)') + $elementPedagogique->getList($qb))
                                                  );

        $qb = $structure->initQuery()[0];
        $structure->join( $service, $qb, 'id', 'structureEns' );
        $service->finderByFilterArray( $serviceContext, $qb );
        $this->get('structure-ens')->setValueOptions( \UnicaenApp\Util::collectionAsOptions(
                                                            array( '' => '(Toutes)') + $structure->getList($qb))
                                                    );

        $qb = $etape->initQuery()[0];
        $etape->join( $elementPedagogique, $qb, 'id', 'etape' );
        $elementPedagogique->join( $service, $qb, 'id', 'elementPedagogique' );
        $service->finderByFilterArray( $serviceContext, $qb );
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
}