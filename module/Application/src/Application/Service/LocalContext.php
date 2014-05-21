<?php

namespace Application\Service;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Etape;
use Application\Entity\NiveauEtape;

/**
 * Classe regroupant des données locales (filtres, etc.)
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class LocalContext extends AbstractContext implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    
    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;
    
    /**
     * @var string
     */
    protected $statutInterv;
    
    /**
     * @var Structure
     */
    protected $structure;
    
    /**
     * @var NiveauEtape
     */
    protected $niveau;
    
    /**
     * @var Etape
     */
    protected $etape;

    /**
     * Constructeur!
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->setEntityManager($entityManager);
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
     * @return Structure
     */
    public function getStructure()
    {
        if (null === $this->structure) {
            $this->structure = $this->getSessionContainer()->structure;
            if ($this->structure && !$this->structure instanceof Structure) {
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
            
            if (is_string($this->niveau)) {
                list($lib, $niv) = explode('-', $this->niveau);
                $this->niveau = NiveauEtape::getInstance($lib, $niv);
            }
        }
        return $this->niveau;
    }

    /**
     * @return Etape
     */
    public function getEtape()
    {
        if (null === $this->etape) {
            $this->etape = $this->getSessionContainer()->etape;
            if ($this->etape && !$this->etape instanceof Etape) {
                $this->etape = $this->getEntityManager()->find("Application\Entity\Db\Etape", $this->etape);
            }
        }
        return $this->etape;
    }

    public function setStatutInterv($statutInterv = null)
    {
        $this->statutInterv = $statutInterv;
        $this->getSessionContainer()->statutInterv = $statutInterv ?: null;
        return $this;
    }

    public function setStructure(Structure $structure = null)
    {
        $this->structure = $structure;
        $this->getSessionContainer()->structure = $structure ? $structure->getId() : null;
        return $this;
    }

    public function setNiveau($niveau = null)
    {             
        $this->niveau = $niveau;
        $this->getSessionContainer()->niveau = $niveau;
        return $this;
    }

    public function setEtape(Etape $etape = null)
    {
        $this->etape = $etape;
        $this->getSessionContainer()->etape = $etape ? $etape->getId() : null;
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
    }
}