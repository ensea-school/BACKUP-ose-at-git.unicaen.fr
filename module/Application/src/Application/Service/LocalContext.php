<?php

namespace Application\Service;

use Zend\Session\Container;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Structure as EntityStructure;
use Application\Entity\Db\Etape as EntityEtape;
use Application\Entity\NiveauEtape as EntityNiveauEtape;
use Application\Entity\Db\ElementPedagogique as EntityElementPedagogique;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


/**
 * Classe regroupant des données locales (filtres, etc.)
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class LocalContext extends AbstractContext implements EntityManagerAwareInterface, ServiceLocatorAwareInterface
{
    use EntityManagerAwareTrait;
    use ServiceLocatorAwareTrait;
    
    /**
     * @var Container
     */
    protected $sessionContainer;
    
    /**
     * @var string
     */
    protected $statutInterv;
    
    /**
     * @var EntityStructure
     */
    protected $structure;
    
    /**
     * @var EntityNiveauEtape
     */
    protected $niveau;
    
    /**
     * @var EntityEtape
     */
    protected $etape;

    /**
     * @var EntityElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @return EntityIntervenant
     */
    public function getIntervenant()
    {
        if (null === $this->intervenant) {
            $this->intervenant = $this->getSessionContainer()->intervenant;
            if ($this->intervenant && !$this->intervenant instanceof EntityIntervenant) {
                $this->intervenant = $this->getEntityManager()->find("Application\Entity\Db\Intervenant", $this->intervenant);
            }
        }
        return $this->intervenant;
    }
    
    /**
     * @return string
     */
    public function getStatutInterv()
    {
        if (null === $this->statutInterv) {
            $this->statutInterv = $this->getSessionContainer()->statutInterv;
        }
        return $this->statutInterv;
    }
    
    /**
     * @return EntityStructure
     */
    public function getStructure()
    {
        if (null === $this->structure) {
            $this->structure = $this->getSessionContainer()->structure;
            if ($this->structure && !$this->structure instanceof EntityStructure) {
                $this->structure = $this->getEntityManager()->find("Application\Entity\Db\Structure", $this->structure);
            }
        }
        return $this->structure;
    }
    
    /**
     * @return NiveauEtape
     */
    public function getNiveau()
    {
        if (null === $this->niveau) {
            $this->niveau = $this->getSessionContainer()->niveau;
            if ($this->niveau && !$this->niveau instanceof EntityNiveauEtape){
                $this->niveau = $this->getServiceNiveauEtape()->get($this->niveau);
            }
        }
        return $this->niveau;
    }

    /**
     * @return EntityEtape
     */
    public function getEtape()
    {
        if (null === $this->etape) {
            $this->etape = $this->getSessionContainer()->etape;
            if ($this->etape && !$this->etape instanceof EntityEtape) {
                $this->etape = $this->getEntityManager()->find("Application\Entity\Db\Etape", $this->etape);
            }
        }
        return $this->etape;
    }

    /**
     * @return EntityElementPedagogique
     */
    public function getElementPedagogique()
    {
        if (null === $this->elementPedagogique) {
            $this->elementPedagogique = $this->getSessionContainer()->elementPedagogique;
            if ($this->elementPedagogique && !$this->elementPedagogique instanceof EntityElementPedagogique) {
                $this->elementPedagogique = $this->getEntityManager()->find("Application\Entity\Db\ElementPedagogique", $this->elementPedagogique);
            }
        }
        return $this->elementPedagogique;
    }

    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Service\LocalContext
     */
    public function setIntervenant(EntityIntervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        $this->getSessionContainer()->intervenant = $intervenant ? $intervenant->getId() : null;
        return $this;
    }

    public function setStatutInterv($statutInterv = null)
    {
        $this->statutInterv = $statutInterv;
        $this->getSessionContainer()->statutInterv = $statutInterv ?: null;
        return $this;
    }

    public function setStructure(EntityStructure $structure = null)
    {
        $this->structure = $structure;
        $this->getSessionContainer()->structure = $structure ? $structure->getId() : null;
        return $this;
    }

    public function setNiveau(EntityNiveauEtape $niveau = null)
    {             
        $this->niveau = $niveau;
        $this->getSessionContainer()->niveau = $niveau ? $niveau->getId() : null;
        return $this;
    }

    public function setEtape(EntityEtape $etape = null)
    {
        $this->etape = $etape;
        $this->getSessionContainer()->etape = $etape ? $etape->getId() : null;
        return $this;
    }

    public function setElementPedagogique(EntityElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique = $elementPedagogique;
        $this->getSessionContainer()->elementPedagogique = $elementPedagogique ? $elementPedagogique->getId() : null;
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
    
    public function fromArray(array $context = array())
    {
        $this->setStructure(isset($context['structureEns']) ? $context['structureEns'] : null);

        return parent::fromArray($context);
    }
    
    public function debug()
    {
        var_dump("statut = " . $this->getStatutInterv());
        var_dump("intervenant = " . $this->getIntervenant());
        var_dump("structure = " . $this->getStructure());
        var_dump("niveau = " . $this->getNiveau());
        var_dump("etape = " . $this->getEtape());
        var_dump("élément pédagogique = " . $this->getElementPedagogique());
    }

    /**
     * @return \Application\Service\NiveauEtape
     */
    protected function getServiceNiveauEtape()
    {
        return $this->getServiceLocator()->get('applicationNiveauEtape');
    }
}