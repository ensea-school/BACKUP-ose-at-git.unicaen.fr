<?php

namespace Application\Service;

use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\NiveauEtapeServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Etape;
use Application\Entity\NiveauEtape;
use Application\Entity\Db\ElementPedagogique;


/**
 * Classe regroupant des données locales (filtres, etc.)
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class LocalContextService extends AbstractService
{
    use IntervenantServiceAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use NiveauEtapeServiceAwareTrait;
    use SessionContainerTrait;

    /**
     * Intervenant
     *
     * @var Intervenant
     */
    protected $intervenant;

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
     * @var ElementPedagogique
     */
    protected $elementPedagogique;



    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        if (empty($this->intervenant)) {
            $this->intervenant = $this->getSessionContainer()->intervenant;
            if ($this->intervenant && !$this->intervenant instanceof Intervenant) {
                $this->intervenant = $this->getServiceIntervenant()->get($this->intervenant);
            }
        }

        return $this->intervenant;
    }



    /**
     * @return Structure
     */
    public function getStructure()
    {
        if (empty($this->structure)) {
            $this->structure = $this->getSessionContainer()->structure;
            if ($this->structure && !$this->structure instanceof Structure) {
                $this->structure = $this->getServiceStructure()->get($this->structure);
            }
        }

        return $this->structure;
    }



    /**
     * @return NiveauEtapeService
     */
    public function getNiveau()
    {
        if (empty($this->niveau)) {
            $this->niveau = $this->getSessionContainer()->niveau;
            if ($this->niveau && !$this->niveau instanceof NiveauEtape) {
                $this->niveau = $this->getServiceNiveauEtape()->get($this->niveau);
            }
        }

        return $this->niveau;
    }



    /**
     * @return Etape
     */
    public function getEtape()
    {
        if (empty($this->etape)) {
            $this->etape = $this->getSessionContainer()->etape;
            if ($this->etape && !$this->etape instanceof Etape) {
                $this->etape = $this->getServiceEtape()->get($this->etape);
            }
        }

        return $this->etape;
    }



    /**
     * @return ElementPedagogique
     */
    public function getElementPedagogique()
    {
        if (empty($this->elementPedagogique)) {
            $this->elementPedagogique = $this->getSessionContainer()->elementPedagogique;
            if ($this->elementPedagogique && !$this->elementPedagogique instanceof ElementPedagogique) {
                $this->elementPedagogique = $this->getServiceElementPedagogique()->get($this->elementPedagogique);
            }
        }

        return $this->elementPedagogique;
    }



    /**
     *
     * @param Intervenant $intervenant
     *
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant = null)
    {
        $this->intervenant                        = $intervenant;
        $this->getSessionContainer()->intervenant = $intervenant ? $intervenant->getId() : null;

        return $this;
    }



    /**
     *
     * @param Structure $structure
     *
     * @return self
     */
    public function setStructure(Structure $structure = null)
    {
        $this->structure                        = $structure;
        $this->getSessionContainer()->structure = $structure ? $structure->getId() : null;

        return $this;
    }



    /**
     *
     * @param NiveauEtape $niveau
     *
     * @return self
     */
    public function setNiveau(NiveauEtape $niveau = null)
    {
        $this->niveau                        = $niveau;
        $this->getSessionContainer()->niveau = $niveau ? $niveau->getId() : null;

        return $this;
    }



    /**
     *
     * @param Etape $etape
     *
     * @return self
     */
    public function setEtape(Etape $etape = null)
    {
        $this->etape                        = $etape;
        $this->getSessionContainer()->etape = $etape ? $etape->getId() : null;

        return $this;
    }



    /**
     *
     * @param ElementPedagogique $elementPedagogique
     *
     * @return self
     */
    public function setElementPedagogique(ElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique                        = $elementPedagogique;
        $this->getSessionContainer()->elementPedagogique = $elementPedagogique ? $elementPedagogique->getId() : null;

        return $this;
    }



    public function fromArray(array $context = [])
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
}