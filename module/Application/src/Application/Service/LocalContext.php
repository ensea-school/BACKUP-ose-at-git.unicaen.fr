<?php

namespace Application\Service;

use Zend\Session\Container;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Structure as EntityStructure;
use Application\Entity\Db\Etape as EntityEtape;
use Application\Entity\NiveauEtape as EntityNiveauEtape;
use Application\Entity\Db\Annee as EntityAnnee;
use Application\Entity\Db\ElementPedagogique as EntityElementPedagogique;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


/**
 * Classe regroupant des données locales (filtres, etc.)
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class LocalContext extends AbstractContext implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var Container
     */
    protected $sessionContainer;

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
        if (empty($this->intervenant)) {
            $this->intervenant = $this->getSessionContainer()->intervenant;
            if ($this->intervenant && !$this->intervenant instanceof EntityIntervenant) {
                $sIntervenant = $this->getServiceLocator()->get('applicationIntervenant');
                /* @var $sIntervenant Intervenant */
                $this->intervenant = $sIntervenant->get( $this->intervenant );
            }
        }
        return $this->intervenant;
    }

    /**
     * @return EntityAnnee
     */
    public function getAnnee()
    {
        if (empty($this->annee)) {
            $this->annee = $this->getSessionContainer()->annee;
            if ($this->annee && !$this->annee instanceof EntityAnnee) {
                $sAnnee = $this->getServiceLocator()->get('applicationAnnee');
                /* @var $sAnnee Annee */
                $this->annee = $sAnnee->get( $this->annee );
            }
        }
        return $this->annee;
    }

    /**
     * @return EntityStructure
     */
    public function getStructure()
    {
        if (empty($this->structure)) {
            $this->structure = $this->getSessionContainer()->structure;
            if ($this->structure && !$this->structure instanceof EntityStructure) {
                $sStructure = $this->getServiceLocator()->get('applicationStructure');
                /* @var $sStructure Structure */
                $this->structure = $sStructure->get( $this->structure );
            }
        }
        return $this->structure;
    }

    /**
     * @return NiveauEtape
     */
    public function getNiveau()
    {
        if (empty($this->niveau)) {
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
        if (empty($this->etape)) {
            $this->etape = $this->getSessionContainer()->etape;
            if ($this->etape && !$this->etape instanceof EntityEtape) {
                $sEtape = $this->getServiceLocator()->get('applicationEtape');
                /* @var $sEtape Etape */
                $this->etape = $sEtape->get( $this->etape );
            }
        }
        return $this->etape;
    }

    /**
     * @return EntityElementPedagogique
     */
    public function getElementPedagogique()
    {
        if (empty($this->elementPedagogique)) {
            $this->elementPedagogique = $this->getSessionContainer()->elementPedagogique;
            if ($this->elementPedagogique && !$this->elementPedagogique instanceof EntityElementPedagogique) {
                $sElementPedagogique = $this->getServiceLocator()->get('applicationElementPedagogique');
                /* @var $sElementPedagogique ElementPedagogique */
                $this->elementPedagogique = $sElementPedagogique->get( $this->elementPedagogique );
            }
        }
        return $this->elementPedagogique;
    }

    /**
     *
     * @param EntityIntervenant $intervenant
     * @return self
     */
    public function setIntervenant(EntityIntervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        $this->getSessionContainer()->intervenant = $intervenant ? $intervenant->getId() : null;
        return $this;
    }

    /**
     *
     * @param EntityAnnee $annee
     * @return self
     */
    public function setAnnee(EntityAnnee $annee = null)
    {
        $this->annee = $annee;
        $this->getSessionContainer()->annee = $annee ? $annee->getId() : null;
        return $this;
    }

    /**
     *
     * @param EntityStructure $structure
     * @return self
     */
    public function setStructure(EntityStructure $structure = null)
    {
        $this->structure = $structure;
        $this->getSessionContainer()->structure = $structure ? $structure->getId() : null;
        return $this;
    }

    /**
     *
     * @param EntityNiveauEtape $niveau
     * @return self
     */
    public function setNiveau(EntityNiveauEtape $niveau = null)
    {
        $this->niveau = $niveau;
        $this->getSessionContainer()->niveau = $niveau ? $niveau->getId() : null;
        return $this;
    }

    /**
     *
     * @param EntityEtape $etape
     * @return self
     */
    public function setEtape(EntityEtape $etape = null)
    {
        $this->etape = $etape;
        $this->getSessionContainer()->etape = $etape ? $etape->getId() : null;
        return $this;
    }

    /**
     *
     * @param EntityElementPedagogique $elementPedagogique
     * @return self
     */
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