<?php

namespace Application\Form\OffreFormation\EtapeCentreCout;

use Application\Entity\Db\CentreCout;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\TypeHeures;
use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutSaisieFieldset;
use Application\Service\CentreCout as CentreCoutService;
use Common\Exception\RuntimeException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Formulaire de saisie, pour chacun des éléments d'une étape, des centres de coûts
 * pour chaque type d'heures éligible.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeCentreCoutSaisieForm extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * 
     */
    public function init()
    {
        $this->setName('etape-centre-cout');
        
        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('EtapeCentreCoutFormHydrator');
        $this->setHydrator($hydrator);
    }
    
    /**
     * 
     * @throws RuntimeException
     */
    protected function build()
    {
        $etape = $this->getEtape();
        if (!$etape) {
            throw new RuntimeException('Etape non spécifiée : construction du formulaire impossible');
        }

        $elements = $etape->getElementPedagogique();
        foreach ($elements as $element) {
            $this->add($this->createFieldset($element));
        }

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden'
        ));

        $this->add(array(
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));
    }
    
    private function createFieldset(ElementPedagogique $element)
    {
        $f = $this->getServiceLocator()->get('ElementCentreCoutSaisieFieldset'); /* @var $f ElementCentreCoutSaisieFieldset */
        $f->setName('EL' . $element->getId());
        
        // fournit au fieldset un moyen de se procurer les centres de coûts sans surcoût(!)
        $f->setForm($this);
        
        return $f;
    }

    /**
     *
     * @param Etape $object
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
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $etape = $this->getEtape();
        if (!$etape) {
            throw new RuntimeException('Etape non spécifiée : construction des filtres du formulaire impossible');
        }

        $elements = $etape->getElementPedagogique();
        $filters  = array();
        foreach ($elements as $element) {
            $filters['EL' . $element->getId()] = array(
                'required' => false
            );
        }

        return $filters;
    }

    /**
     * Types d'heures.
     *
     * @var TypeHeures[]
     */
    protected $typesHeures;

    /**
     * Recherche, parmi les éléments de l'étape, des types d'heures distincts éligibles
     *
     * @return TypeHeures[]
     */
    public function getTypesHeures()
    {
        if (null === $this->typesHeures) {
            if (!$this->getEtape()) {
                throw new RuntimeException('Aucune étape spécifiée.');
            }

            $serviceTypeHeures = $this->getServiceLocator()->getServiceLocator()->get('applicationTypeHeures'); /* @var $serviceTypeHeures TypeHeuresService */
            $qb                = $serviceTypeHeures->initQuery()[0]; /* @var $qb QueryBuilder */
            $qb
                    ->join("th.elementPedagogique", "ep")
                    ->andWhere("ep.etape = :etape")->setParameter('etape', $this->getEtape())
                    ->andWhere("th.eligibleCentreCoutEp = 1")
                    ->distinct();
            
            $this->typesHeures = $serviceTypeHeures->getList($qb);
        }
        
        return $this->typesHeures;
    }

    /**
     * Centres de couts pour chaque (code de) type d'heures.
     *
     * @var CentreCout[]
     */
    protected $centresCouts = [];
    
    /**
     * Retourne les centres de coûts possibles pour le type d'heure spécifié.
     *
     * @return CentreCout[]
     */
    protected function getCentresCouts(TypeHeures $th)
    {
        if (!array_key_exists($th->getCode(), $this->centresCouts)) {
            $serviceCentreCout = $this->getServiceCentreCout();
            $qb = $serviceCentreCout->finderByStructure($this->getEtape()->getStructure())
                    ->join("cc.typeHeures", "th", Join::WITH, "th = :th")->setParameter('th', $th)
                    ->leftJoin("cc.parent", "ccp")
                    ->addSelect("ccp")
                    ->addOrderBy("ccp.sourceCode", "ASC") // ne pas changer le tri
                    ->addOrderBy("cc.sourceCode", "ASC");
            $this->centresCouts[$th->getCode()] = $serviceCentreCout->getList($qb);
        }
        
        return $this->centresCouts[$th->getCode()];
    }

    /**
     * Centres de couts pour chaque (code de) type d'heures.
     *
     * @var array
     */
    protected $centresCoutsToArray = [];
    
    /**
     * Retourne les centres de coûts possibles pour le type d'heure spécifié.
     *
     * @return array
     */
    public function getCentresCoutsToArray(TypeHeures $th)
    {
        if (!array_key_exists($th->getCode(), $this->centresCoutsToArray)) {
            $this->centresCoutsToArray[$th->getCode()] = $this->getServiceCentreCout()->formatCentresCouts($this->getCentresCouts($th));
        }
        
        return $this->centresCoutsToArray[$th->getCode()];
    }
    
    /**
     * @var Etape
     */
    protected $etape;

    public function getEtape()
    {
        return $this->etape;
    }

    public function setEtape(Etape $etape)
    {
        $this->etape = $etape;
        return $this;
    }   
    
    /**
     * @return CentreCoutService
     */
    private function getServiceCentreCout()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationCentreCout');
    }
}